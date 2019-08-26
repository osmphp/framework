<?php

namespace Osm\Core\Modules;

use Osm\Core\App;
use Osm\Core\Exceptions\CircularDependency;
use Osm\Core\Exceptions\ModuleNotFound;
use Osm\Core\Object_;
use Osm\Core\Packages\ComponentPool;

class ModuleLoader extends Object_
{
    /**
     * @return BaseModule[]
     */
    public function load() {
        $modules = [];

        $this->loadFiles($modules);
        $this->sort($modules);

        return $modules;
    }

    /**
     * @param BaseModule[] $modules
     */
    protected function loadFiles(&$modules) {
        global $m_app; /* @var App $m_app */

        foreach ($m_app->packages as $package) {
            foreach ($package->component_pools as $pool) {
                if (!$pool->module_path || !$pool->namespace) {
                    continue;
                }

                if (env('APP_ENV') != 'testing' && $pool->testing) {
                    continue;
                }

                    $this->loadFilesFrom($modules, $pool);
            }
        }
    }

    /**
     * @param BaseModule[] $modules
     * @param ComponentPool $pool
     */
    protected function loadFilesFrom(&$modules, $pool) {
        global $m_app; /* @var App $m_app */

        $path = $m_app->path($pool->parent->path .
            ($pool->name && $pool->parent->path ? '/' : '') .
            $pool->name);
        foreach (glob("{$path}/{$pool->module_path}") as $filename) {
            if ($m_app->ignore($filename)) {
                continue;
            }

            $name = $pool->namespace . '_' . str_replace('/', '_', str_replace('\\', '/',
                substr(dirname($filename), strlen($path) + 1)));

            $data = [
                'path' => str_replace('\\', '/',
                    substr(dirname($filename), strlen($m_app->base_path) + 1)),
                'class' => str_replace('_', '\\', $name) . "\\Module",
                'package' => $pool->parent->name,
                'component_pool' => $pool->name,
            ];

            $modules[$name] = $module = BaseModule::new($data, $name, $m_app);
        }
    }

    /**
     * @param BaseModule[] $modules
     */
    protected function sort(&$modules) {
        $count = count($modules);
        $positions = [];

        for ($position = 0; $position < $count; $position++) {
            if (! ($moduleName = $this->findModuleWithAlreadyResolvedDependencies($modules, $positions))) {
                throw $this->circularDependency($modules, $positions);
            }

            $positions[$moduleName] = $position;
        }

        $this->sortByPosition($modules, $positions);
    }

    /**
     * @param BaseModule[] $modules
     * @param int[] $positions
     * @return bool|string
     */
    protected function findModuleWithAlreadyResolvedDependencies($modules, $positions) {
        foreach ($modules as $moduleName => $module) {
            if (isset($positions[$moduleName])) {
                continue;
            }

            if ($this->moduleHasUnresolvedHardDependency($module, $modules, $positions)) {
                continue;
            }

            if ($this->moduleHasUnresolvedSoftDependency($module, $modules, $positions)) {
                continue;
            }

            return $moduleName;
        }

        return false;
    }

    /**
     * @param BaseModule[] $modules
     * @param int[] $positions
     * @return CircularDependency
     */
    protected function circularDependency($modules, $positions) {
        $circular = array();
        foreach ($modules as $moduleName => $module) {
            if (!isset($positions[$moduleName])) {
                $circular[] = $moduleName;
            }
        }
        return new CircularDependency(sprintf('Modules with circular dependencies found: %s',
            implode(', ', $circular)));
    }


    /**
     * @param BaseModule $module
     * @param BaseModule[] $modules
     * @param int[] $positions
     * @return bool
     */
    protected function moduleHasUnresolvedHardDependency($module, $modules, $positions) {
        if (!isset($module->hard_dependencies)) {
            return false;
        }

        foreach ($module->hard_dependencies as $dependency) {
            if (!isset($modules[$dependency])) {
                throw new ModuleNotFound("'$dependency' mentioned as dependency for '{$module->name}' ".
                    "not found in list of modules.");
            }

            if (!isset($positions[$dependency])) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param BaseModule $module
     * @param BaseModule[] $modules
     * @param int[] $positions
     * @return bool
     */
    protected function moduleHasUnresolvedSoftDependency($module, $modules, $positions) {
        if (isset($module->soft_dependencies)) {
            foreach ($module->soft_dependencies as $dependency) {
                if (isset($modules[$dependency]) && !isset($positions[$dependency])) {
                    return true;
                }
            }
        }

        return false;
    }

    /**
     * @param BaseModule[] $modules
     * @param int[] $positions
     */
    protected function sortByPosition(&$modules, $positions) {
        uasort($modules, function ($a, $b) use ($positions) {
            $a = $positions[$a->name];
            $b = $positions[$b->name];
            if ($a == $b) return 0;

            return $a < $b ? -1 : 1;
        });
    }
}