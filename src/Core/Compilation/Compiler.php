<?php

namespace Osm\Core\Compilation;

use Osm\Core\App;
use Osm\Core\Classes\Classes;
use Osm\Core\Modules\BaseModule;
use Osm\Core\Object_;
use Osm\Core\Properties;

/**
 * @property string $filename @required @part
 * @property Weaver $weaver @required @part
 * @property Generator $generator @required @part
 */
class Compiler extends Object_
{
    /**
     * @temp
     * @var Class_[]
     */
    public $classes = [];

    public function default($property) {
        global $m_app; /* @var App $m_app */

        switch ($property) {
            case 'filename': return m_path("{$m_app->temp_path}/cache/classes.php");
            case 'weaver': return Weaver::new(['parent' => $this]);
            case 'generator': return Generator::new(['parent' => $this]);
        }
        return parent::default($property);
    }

    public function includeGeneratedClasses() {
        /** @noinspection PhpIncludeInspection */
        include_once $this->filename;
    }

    public function compile() {
        global $m_app; /* @var App $m_app */

        $this->collectTraits();
        $this->collectClasses();
        $this->addHintsToClasses();
        $this->propagateTraits();
        $this->propagateHints();
        $this->defineHintProperties();
        $this->removeUnaffectedClasses();
        $this->generateClasses();

        $m_app->class_names = array_map(function(Class_ $class) {
            return $class->generated_name;
        }, $this->classes);
        $m_app->modified();
    }

    protected function collectTraits() {
        global $m_app; /* @var App $m_app */

        foreach ($m_app->modules as $module) {
            if (!$module->traits) {
                continue;
            }

            foreach ($module->traits as $class => $traits) {
                if (!is_array($traits)) {
                    $traits = [$traits];
                }

                if (isset($this->classes[$class])) {
                    $this->classes[$class]->traits = array_merge($this->classes[$class]->traits, $traits);
                }
                else {
                    $this->classes[$class] = Class_::new(['traits' => $traits], $class);
                }
            }
        }
    }

    protected function collectClasses() {
        global $m_app; /* @var App $m_app */

        foreach ($m_app->modules as $module) {
            $this->collectModuleClasses($module, '');
        }
    }

    protected function collectModuleClasses(BaseModule $module, $path) {
        global $m_app; /* @var App $m_app */

        foreach (new \DirectoryIterator($m_app->path($module->path .($path ? "/$path" : ''))) as $fileInfo)
        {
            if ($fileInfo->isDot()) {
                continue;
            }

            if (!preg_match('/^[A-Z]/', $fileInfo->getFilename())) {
                continue;
            }

            if ($fileInfo->isDir()) {
                $this->collectModuleClasses($module, ($path ? "{$path}/" : '') . $fileInfo->getFilename());
                continue;
            }

            if ($fileInfo->getExtension() != 'php') {
                continue;
            }

            $class = str_replace('_', '\\', $module->name) . '\\' .
                str_replace('/', '\\',
                    ($path ? "{$path}/" : '') . pathinfo($fileInfo->getFilename(), PATHINFO_FILENAME));

            if (!isset($this->classes[$class])) {
                $this->classes[$class] = Class_::new(['traits' => []], $class);
            }
        }

    }

    protected function propagateTraits() {
        foreach (array_keys($this->classes) as $class) {
            $this->propagateTraitsInClass($class);
        }
    }

    protected function propagateTraitsInClass($class) {
        if (!isset($this->classes[$class])) {
            $this->classes[$class] = Class_::new(['traits' => []], $class);
        }

        if ($this->classes[$class]->propagated) {
            return;
        }

        $reflection = new \ReflectionClass($class);
        if (!$reflection->getParentClass()) {
            return;
        }

        $parentClass = $reflection->getParentClass()->getName();
        $this->propagateTraitsInClass($parentClass);

        $this->classes[$class]->traits = array_unique(array_merge($this->classes[$class]->traits,
            $this->classes[$parentClass]->traits));
    }

    protected function removeUnaffectedClasses() {
        $this->classes = array_filter($this->classes, function (Class_ $class) {
            return !empty($class->traits);
        });
    }

    protected function generateClasses() {
        global $m_app; /* @var App $m_app */

        $output = "<?php\n\n";

        foreach ($this->classes as $class) {
            if (is_a($class->name, BaseModule::class, true)) {
                continue;
            }

            $output .= $this->generateClass($class);
        }

        file_put_contents(m_make_dir_for($this->filename), $output);
        @chmod($this->filename, $m_app->writable_file_permissions);
    }

    protected function generateClass($class) {
        $this->weaver->class_ = $class;
        $this->weaver->weave();

        $this->generator->class_ = $class;
        return $this->generator->generate();
    }

    protected function isHintClass($class) {
        return preg_match('/\\\\Hints\\\\[a-zA-Z0-9_]*Hint$/', $class);
    }

    protected function addHintsToClasses() {
        global $m_classes; /* @var Classes $m_classes */

        foreach (array_keys($this->classes) as $class) {
            $hint = $m_classes->get($class);
            if (!$hint['hint']) {
                continue;
            }

            $parent = $hint['parent'];
            while ($parent) {
                $parent_ = &$m_classes->get($parent);
                if ($parent_['hint']) {
                    $parent = $parent_['parent'];
                    continue;
                }

                $parent_['direct_hints'][] = $class;
                break;
            }
        }
    }

    protected function propagateHints() {
        global $m_classes; /* @var Classes $m_classes */

        foreach ($m_classes->items as &$class) {
            $this->propagateHintsInClass($class);
        }
    }

    protected function propagateHintsInClass(&$class) {
        global $m_classes; /* @var Classes $m_classes */

        if ($class['hint']) {
            $class['hints'] = [];
            return;
        }

        if (isset($class['hints'])) {
            return;
        }

        if (!$class['parent']) {
            $class['hints'] = $class['direct_hints'];
            return;
        }

        $parent = &$m_classes->get($class['parent']);
        $this->propagateHintsInClass($parent);

        $class['hints'] = array_merge($class['direct_hints'], $parent['hints']);
    }

    protected function defineHintProperties() {
        global $m_classes; /* @var Classes $m_classes */
        foreach ($m_classes->items as &$class) {
            if (!isset($class['hints'])) {
                continue;
            }

            foreach ($class['hints'] as $hint) {
                $class['properties'] = array_merge($class['properties'], $m_classes->get($hint)['properties']);
            }
        }
    }
}