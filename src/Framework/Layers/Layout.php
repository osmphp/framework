<?php

namespace Manadev\Framework\Layers;

use Manadev\Core\App;
use Manadev\Core\Exceptions\NotFound;
use Manadev\Framework\Areas\Area;
use Manadev\Framework\Cache\Cache;
use Manadev\Core\Object_;
use Manadev\Framework\Layers\Exceptions\InvalidInstruction;
use Manadev\Framework\Themes\Current;
use Manadev\Framework\Themes\Theme;
use Manadev\Framework\Views\Iterator;
use Manadev\Framework\Views\View;
use Psr\Log\LoggerInterface;

/**
 * @property Cache $cache @required
 * @property Area $area @required
 * @property Theme $theme @required
 * @property string $path @required
 * @property View $root @required
 * @property Iterator $iterator @required
 * @property LoggerInterface $log @required
 */
class Layout extends Object_
{
    /**
     * Views with assigned ID are kept in this array for fast retrieval
     * @var View[]
     */
    public $views = [];
    /**
     * @var string[]
     */
    public $included = [];

    protected function default($property) {
        global $m_app; /* @var App $m_app */

        switch ($property) {
            case 'cache': return $m_app->cache;
            case 'area': return $m_app->area_;
            case 'theme': return $m_app->themes[$m_app[Current::class]->get($this->area->name)];
            case 'iterator': return $m_app[Iterator::class];
            case 'path': return $m_app->path("{$m_app->temp_path}/layers/{$this->area->name}/" .
                $this->theme->name);
            case 'log': return $m_app->logs->layers;
        }
        return parent::default($property);
    }

    public function load(...$layers) {
        global $m_app; /* @var App $m_app */

        $m_app->layout = $this;

        foreach ($layers as $layer) {
            $this->loadLayer($layer);
        }

        return $this;
    }

    public function __toString() {
        return (string)$this->root;
    }

    /**
     * @param string|callable|array $layer
     */
    public function loadLayer($layer) {
        if (is_callable($layer)) {
            $layer = $layer();
        }

        if (is_array($layer)) {
            $this->processLayer($layer);
            return;
        }

        if (isset($this->included[$layer])) {
            return;
        }
        $this->included[$layer] = true;

        foreach ($this->loadNamedLayerFromCache($layer) as $loadedLayer) {
            $this->processLayer($loadedLayer);
        }
    }

    /**
     * @param string $layer
     * @return array
     */
    protected function loadNamedLayerFromCache($layer) {
        $key = "layer_{$this->area->name}_{$this->theme->name}_{$layer}";
        if (($result = $this->cache->get($key)) !== null) {
            return $result;
        }

        $result = $this->loadNamedLayer($layer);
        $this->cache->put($key, $result);
        return $result;
    }

    /**
     * @param string $layer
     * @return array
     */
    protected function loadNamedLayer($layer) {
        global $m_app; /* @var App $m_app */

        $result = [];
        $exists = false;
        foreach ($m_app->modules as $module) {
            $filename = "{$this->path}/{$module->name}/{$layer}.php";
            if (file_exists($filename)) {
                $result[$filename] = $this->loadFile($filename);
                $this->log->info(m_("{filename} loaded."), ['filename' => $filename]);
                $exists = true;
            }
        }

        if (!$exists) {
            throw new NotFound(m_("Layout layer ':layer' not found", ['layer' => $layer]));
        }
        return $result;
    }

    protected function loadFile($__filename) {
        /** @noinspection PhpIncludeInspection */
        return include $__filename;
    }

    /**
     * @param array $layer
     */
    protected function processLayer($layer) {
        foreach ($layer as $key => $value) {
            $this->processInstruction($key, $value);
        }
    }


    protected function processInstruction($instruction, $value) {
        if (starts_with($instruction, '#')) {
            if ($view = $this->select($instruction)) {
                $this->merge($view, $value);
            }
            return;
        }

        if (starts_with($instruction, '@')) {
            if (($pos = strpos($instruction, ' ')) !== false) {
                $this->{'process' . studly_case(substr($instruction, 1, $pos - 1)) . 'Directive'}($value,
                    substr($instruction, $pos + 1));
            }
            else {
                $this->{'process' . studly_case(substr($instruction, 1)) . 'Directive'}($value);
            }
            return;
        }

        if ($instruction == ucfirst($instruction)) {
            foreach ($this->findViewsByClass($instruction) as $view) {
                $this->merge($view, $value);
            }
            return;
        }

        if ($instruction == 'root') {
            $this->root = $value;
            $this->registerViewRecursively($this->root);
            return;
        }

        throw new InvalidInstruction(m_("Invalid layer instruction ':instruction'",
            ['instruction' => $instruction]));
    }

    protected function processIncludeDirective($layers) {
        if (!is_array($layers)) {
            $layers = [$layers];
        }

        foreach ($layers as $layer) {
            $this->loadLayer($layer);
        }
    }

    protected function processAssignDirective($value, $selector) {
        $this->assign($selector, $value);
    }

    protected function processMoveDirective($target, $source) {
        $this->assign($target, $this->select($source));
    }

    /**
     * @param $class
     * @return \Generator|View[]
     */
    protected function findViewsByClass($class) {
        return $this->iterator->iterateRecursively($this->root, function($view) use ($class){
            return is_a($view, $class);
        });
    }

    /**
     * @param $selector
     * @return View
     */
    public function select($selector) {
        if (($pos = mb_strpos($selector, '.')) !== false) {
            $path = mb_substr($selector, $pos + 1);
            $selector = mb_substr($selector, 1, $pos - 1);
        }
        else {
            $path = null;
            $selector = mb_substr($selector, 1);
        }

        if (!isset($this->views[$selector])) {
            return null;
        }

        $result = $this->views[$selector];

        if ($path === null) {
            return $result;
        }

        foreach (explode('.', $path) as $property) {
            if (($pos = mb_strpos($property, '[')) !== false) {
                $index = mb_substr($property, $pos + 1, mb_strlen($property) - $pos - 2);
                $property = mb_substr($property, 0, $pos);
            }
            else {
                $index = null;
            }

            if (!isset($result->$property)) {
                return null;
            }

            $result = $result->$property;

            if ($index !== null) {
                $result = $result[$index];
            }
        }

        return $result;
    }

    protected function assign($selector, $value) {
        $pos = mb_strrpos($selector, '.');
        $target = $this->select(mb_substr($selector, 0, $pos));
        $property = mb_substr($selector, $pos + 1);

        for ($parent = $target; !($parent instanceof View); ) {
            $parent = $parent->parent;
        }

        if (($pos = mb_strpos($property, '[')) !== false) {
            $index = mb_substr($property, $pos + 1, mb_strlen($property) - $pos - 2);
            $property = mb_substr($property, 0, $pos);

            $data = [$property => [$index => $value]];
            $parent->assignSelfAsParentTo($data);

            $target->$property[$index] = $value;
        }
        else {
            $data = [$property => $value];
            $parent->assignSelfAsParentTo($data);

            $target->$property = $value;
        }

        $this->registerDataRecursively($data);
    }

    protected function merge(View $view, $value) {
        $view->assignSelfAsParentTo($value);
        m_merge($view, $value);
        $this->registerDataRecursively($value);
    }

    protected function registerViewRecursively(View $parent) {
        foreach ($this->iterator->iterateRecursively($parent) as $view) {
            if (!$view->id) {
                continue;
            }

            if (!isset($this->views[$view->id])) {
                $this->views[$view->id] = $view;
                continue;
            }

            /** @noinspection PhpNonStrictObjectEqualityInspection */
            if ($this->views[$view->id] != $view) {
                throw new InvalidInstruction(m_("Two or more views have the same id ':id'", ['id' => $view->id]));
            }
        }
    }

    protected function registerDataRecursively($data) {
        foreach ($this->iterator->iterateData($data) as $view) {
            $this->registerViewRecursively($view);
        }
    }

    /**
     * @return Layout
     */
    public function prepare() {
        $prepared = [];
        do {
            $foundUnprepared = false;

            foreach ($this->iterator->iterateRecursively($this->root) as $view) {
                if (isset($prepared[$hash = spl_object_hash($view)])) {
                    continue;
                }
                $prepared[$hash] = true;
                $foundUnprepared = true;

                $view->prepare();
            }
        } while($foundUnprepared);

        return $this;
    }
}