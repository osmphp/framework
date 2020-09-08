<?php

namespace Osm\Framework\Cache\Traits;

use Osm\Core\App;
use Osm\Framework\Cache\Caches;

trait PropertiesTrait
{
    public function Osm_Core_App__caches(App $app) {
        return Caches::create();
    }

    public function Osm_Core_App__cache(App $app) {
        return $app->caches[env('CACHE', 'file')];
    }
}