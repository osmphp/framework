<?php

namespace Osm\Framework\Layers;

use Osm\Core\App;
use Osm\Core\Exceptions\NotFound;
use Osm\Framework\Areas\Area;
use Osm\Framework\Cache\Cache;
use Osm\Core\Object_;
use Osm\Framework\Layers\Exceptions\InvalidInstruction;
use Osm\Framework\Themes\Current;
use Osm\Framework\Themes\Theme;
use Osm\Framework\Views\Iterator;
use Osm\Framework\Views\View;
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

    /**
     * @temp
     *
     * @var bool
     */
    public $replacing = false;

    protected function default($property) {
        global $osm_app; /* @var App $osm_app */

        switch ($property) {
            case 'cache': return $osm_app->cache;
            case 'area': return $osm_app->area_;
            case 'theme': return $osm_app->themes[$osm_app[Current::class]->get($this->area->name)];
            case 'iterator': return $osm_app[Iterator::class];
            case 'path': return $osm_app->path("{$osm_app->temp_path}/layers/{$this->area->name}/" .
                $this->theme->name);
            case 'log': return $osm_app->logs->layers;
        }
        return parent::default($property);
    }

    public function __construct($data = []) {
        global $osm_app; /* @var App $osm_app */

        $osm_app->layout = $this;

        parent::__construct($data);
    }

    public function load(...$layers) {
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
        global $osm_app; /* @var App $osm_app */

        $result = [];
        $exists = false;
        foreach ($osm_app->modules as $module) {
            $filename = "{$this->path}/{$module->name}/{$layer}.php";
            if (file_exists($filename)) {
                $result[$filename] = $this->loadFile($filename);
                $this->log->info(osm_t("{filename} loaded."), ['filename' => $filename]);
                $exists = true;
            }
        }

        if (!$exists) {
            throw new NotFound(osm_t("Layout layer ':layer' not found", ['layer' => $layer]));
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
            if ($view = $this->select($instruction, $property, $index)) {
                $this->merge($view, $value, $property, $index);
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

        throw new InvalidInstruction(osm_t("Invalid layer instruction ':instruction'",
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

    protected function processReplaceDirective($value, $selector) {
        $this->replacing = true;
        $this->assign($selector, $value);
        $this->replacing = false;
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
     * @param string $selector
     * @param string $property
     * @param int|string $index
     * @return View
     */
    public function select($selector, &$property = null, &$index = null) {
        if (($pos = mb_strpos($selector, '.')) !== false) {
            // split '#id.foo.bar' into 'id' and 'foo.bar'
            $path = mb_substr($selector, $pos + 1);
            $selector = mb_substr($selector, 1, $pos - 1);
        }
        else {
            // cut '#' from '#id'
            $path = null;
            $selector = mb_substr($selector, 1);
        }

        if (!isset($this->views[$selector])) {
            // search by id failed - view not found
            return null;
        }

        // find view object by id
        $result = $object = $this->views[$selector];

        if ($path === null) {
            // no properties or array indexes in the layer instruction - just
            // return the view object
            return $result;
        }

        $path = explode('.', $path);

        // go through property path by processing one property at a time.
        // Note that $property and $index are not just local variables, but
        // passed to the caller by reference
        foreach ($path as $i => $property) {
            // if there is an index in the instruction, split array
            // property name and index
            if (($pos = mb_strpos($property, '[')) !== false) {
                $index = mb_substr($property, $pos + 1, mb_strlen($property) - $pos - 2);
                $property = mb_substr($property, 0, $pos);
            }
            else {
                $index = null;
            }

            // find child object referenced in the instruction
            $object = $object->$property ?? null;
            if ($index !== null) {
                $object = $object[$index];
            }

            if ($object instanceof View) {
                // if it's view, we will return it to the caller
                $result = $object;
                $property = null;
                $index = null;
                continue;
            }

            if ($i !== count($path) - 1) {
                // if it's not view, we will return the last object that
                // IS view and the property name and index in it.

                // However, property/index can only be the last part in the
                // instruction path. If that's not the case, return that
                // view is not found
                return null;
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

    protected function merge(View $view, $value, $property = null, $index = null) {
        if ($index !== null) {
            $value = [$index => $value];
        }
        if ($property !== null) {
            $value = [$property => $value];
        }

        $view->assignSelfAsParentTo($value);

        osm_merge($view, $value);

        $this->registerDataRecursively($value);
    }

    protected function registerViewRecursively(View $parent) {
        foreach ($this->iterator->iterateRecursively($parent) as $view) {
            if (!$view->id) {
                continue;
            }

            if (!isset($this->views[$view->id]) || $this->replacing) {
                $this->views[$view->id] = $view;
                continue;
            }

            if ($this->views[$view->id] !== $view) {
                throw new InvalidInstruction(osm_t("Two or more views have the same id ':id'", ['id' => $view->id]));
            }
        }
    }

    protected function registerDataRecursively($data) {
        if (!is_iterable($data)) {
            return;
        }

        foreach ($this->iterator->iterateData($data) as $view) {
            $this->registerViewRecursively($view);
        }
    }

    public function view(View $view, View $value, $property, $index = null) {
        $view->assignSelfAsParentTo($value, $property, $index);
        $this->registerViewRecursively($value);
        return $value;
    }
}