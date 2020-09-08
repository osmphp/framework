<?php

namespace Osm\Framework\Encryption\Traits;

use Osm\Core\App;
use Osm\Framework\Encryption\Module;

trait PropertiesTrait
{
    public function Osm_Core_App__hashing(App $app) {
        $module = $app->modules['Osm_Framework_Encryption']; /* @var Module $module */
        return $module->hashings[$app->settings->hashing_algorithm];
    }

}