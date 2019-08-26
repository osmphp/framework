<?php

namespace Osm\Core\Modules;

use Osm\Core\App;
use Osm\Core\Object_;

/**
 * @property BaseModule[] $modules @required
 */
class ModuleHelper extends Object_
{
    public function default($property) {
        global $osm_app; /* @var App $osm_app */

        switch ($property) {
            case 'modules': return $osm_app->modules;
        }
        return parent::default($property);
    }

    public function getModules($moduleNames) {
        $result = [];

        foreach ($moduleNames as $moduleName) {
            $result[$moduleName] = $moduleName;
        }

        return $this->doGetModules($result);
    }

    public function getModulesAndDependencies($moduleNames) {
        if (!is_array($moduleNames)) {
            $moduleNames = [$moduleNames];
        }

        $result = [];

        foreach ($moduleNames as $moduleName) {
            $result[$moduleName] = $moduleName;
            $this->populateDependencies($result, $moduleName);
        }

        return $this->doGetModules($result);
    }

    protected function populateDependencies(&$result, $moduleName) {
        $sources = [
            $this->modules[$moduleName]->hard_dependencies,
            $this->modules[$moduleName]->soft_dependencies,
        ];

        foreach ($sources as $dependencies) {
            if (empty($dependencies)) {
                continue;
            }

            foreach ($dependencies as $dependency) {
                if (isset($result[$dependency])) {
                    continue;
                }

                $result[$dependency] = $dependency;
                $this->populateDependencies($result, $dependency);
            }
        }
    }

    public function getModulesAndDependents($moduleNames) {
        if (!is_array($moduleNames)) {
            $moduleNames = [$moduleNames];
        }

        $result = [];

        foreach ($moduleNames as $moduleName) {
            $result[$moduleName] = $moduleName;
            $this->populateDependents($result, $moduleName);
        }

        return $this->doGetModules($result);
    }

    protected function populateDependents(&$result, $moduleName) {
        foreach ($this->modules as $module) {
            if (isset($result[$module->name])) {
                continue;
            }

            $sources = [
                $module->hard_dependencies,
                $module->soft_dependencies,
            ];

            foreach ($sources as $dependencies) {
                if (empty($dependencies)) {
                    continue;
                }

                if (in_array($moduleName, $dependencies)) {
                    $result[$module->name] = $module->name;
                    $this->populateDependents($result, $module->name);
                    break;
                }
            }
        }
    }

    protected function doGetModules($moduleNames) {
        return array_filter($this->modules, function(BaseModule $module) use ($moduleNames) {
            return isset($moduleNames[$module->name]);
        });
    }
}