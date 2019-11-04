<?php

namespace Osm\Framework\Sessions\Traits;

use Osm\Core\App;
use Osm\Framework\Areas\Area;
use Osm\Framework\Sessions\Stores;

trait PropertiesTrait
{
    public function Osm_Core_App__session_stores(App $app) {
        return $app->cache->remember('session_stores', function($data) {
            return Stores::new($data);
        });
    }

    public function Osm_Framework_Areas_Area__sessions(Area $area) {
        global $osm_app; /* @var App $osm_app */

        return $osm_app->session_stores[$area->name] ?? null;
    }
}