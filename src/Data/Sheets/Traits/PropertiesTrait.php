<?php

namespace Osm\Data\Sheets\Traits;

use Osm\Core\App;
use Osm\Data\Sheets\Sheets;

trait PropertiesTrait
{
    public function Osm_Core_App__sheets(App $app) {
        return $app->cache->remember("sheets", function($data) {
            return Sheets::new($data);
        });
    }
}