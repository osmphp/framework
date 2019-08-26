<?php

namespace Osm\Framework\Settings\Traits;

use Osm\Core\App;
use Osm\Framework\Settings\Settings;

trait PropertiesTrait
{
    public function Osm_Core_App__settings(App $app) {
        return $app->cache->remember('settings', function($data) {
            return Settings::new($data);
        });
    }

}