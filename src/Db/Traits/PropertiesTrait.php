<?php

namespace Osm\Framework\Db\Traits;

use Osm\Core\App;
use Osm\Framework\Db\Databases;

trait PropertiesTrait
{
    public function Osm_Core_App__databases(App $app) {
        return $app->cache->remember("databases", function($data) {
            return Databases::new($data);
        });
    }

    public function Osm_Core_App__db(App $app) {
        return $app->databases['main'];
    }

}