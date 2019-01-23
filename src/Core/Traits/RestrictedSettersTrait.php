<?php

namespace Manadev\Core\Traits;

use Manadev\Core\App;
use Manadev\Core\Exceptions\CantSetProperty;
use Manadev\Core\Modules\BaseModule;
use Manadev\Core\Object_;

trait RestrictedSettersTrait
{
    public function __set($property, $value) {
        $classIndex = 0;
        $typeIndex = 1;
        $functionIndex = 2;
        $targetIndex = 3;
        $propertyIndex = 4;
        $moduleSetterLength = 5;

        $this->$property = $value;

        static $allowedCoreMethods;
        if (!$allowedCoreMethods) {
            $allowedCoreMethods = [
                [Object_::class, '->', '__construct'],
                [Object_::class, '->', '__get'],
                [Object_::class, '->', '__wakeup'],
                [App::class, '::', 'createApp'],
                [App::class, '::', 'runApp'],
                [BaseModule::class, '->', 'boot'],
            ];
        }

        static $allowedModuleMethods;
        if (!$allowedModuleMethods) {
            $allowedModuleMethods = [];
        }
        $appKey = null;
        if (($app = App::$instance) && isset($app->modules) && ($appKey = spl_object_hash($app)) &&
            !isset($allowedModuleMethods[$appKey]))
        {
            $allowedModuleMethods[$appKey] = [];
            foreach ($app->modules as $module) {
                if (!$module->setters) {
                    continue;
                }

                foreach ($module->setters as $setter) {
                    if (count($setter) == $moduleSetterLength) {
                        $allowedModuleMethods[$appKey][] = $setter;
                    }
                }
            }
        }

        try {
            throw new \Exception();
        }
        catch (\Exception $e) {
            foreach ($e->getTrace() as $entry) {
                if (isset($entry['class']) && isset($entry['function']) && isset($entry['type'])) {
                    foreach ($allowedCoreMethods as $method) {
                        if ($entry['type'] === $method[$typeIndex] &&
                            $entry['function'] === $method[$functionIndex] &&
                            $entry['class'] === $method[$classIndex])
                        {
                            return;
                        }
                    }

                    if ($appKey) {
                        foreach ($allowedModuleMethods[$appKey] as $method) {
                            if ($entry['type'] === $method[$typeIndex] &&
                                $entry['function'] === $method[$functionIndex] &&
                                $property === $method[$propertyIndex] &&
                                is_a($entry['class'], $method[$classIndex], true) &&
                                is_a($this, $method[$targetIndex]))
                            {
                                return;
                            }
                        }

                    }
                }
            }

            throw new CantSetProperty("Setting property '$property' in class '" . get_class($this) .
                "' is not allowed from \n\n" . $e->getTraceAsString());
        }

    }
}