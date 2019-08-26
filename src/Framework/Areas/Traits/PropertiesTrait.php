<?php

namespace Osm\Framework\Areas\Traits;

use Osm\Core\App;
use Osm\Framework\Areas\Areas;

trait PropertiesTrait
{
    public function Osm_Core_App__areas(App $app) {
        return $app->cache->remember('areas', function($data) {
            return Areas::new($data);
        });
    }
    public function Osm_Core_App__area_(App $app) {
        return $app->areas[$app->area];
    }

}