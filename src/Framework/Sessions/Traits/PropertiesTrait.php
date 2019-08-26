<?php

namespace Osm\Framework\Sessions\Traits;

use Osm\Core\App;
use Osm\Framework\Sessions\Stores;

trait PropertiesTrait
{
    public function Osm_Core_App__session_stores(App $app) {
        return $app->cache->remember('session_stores', function($data) {
            return Stores::new($data);
        });
    }
}