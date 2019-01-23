<?php

namespace Manadev\Framework\Encryption\Traits;

use Manadev\Core\App;
use Manadev\Framework\Encryption\Module;

trait PropertiesTrait
{
    public function Manadev_Core_App__hashing(App $app) {
        $module = $app->modules['Manadev_Framework_Encryption']; /* @var Module $module */
        return $module->hashings[$app->settings->hashing_algorithm];
    }

}