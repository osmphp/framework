<?php

namespace Osm\Framework\Http\Traits;

use Osm\Core\App;
use Osm\Framework\Http\Query;
use Osm\Framework\Http\Request;

trait PropertiesTrait
{
    public function Osm_Core_App__request(App $app) {
        return Request::new();
    }

    public function Osm_Core_App__url(App $app) {
        return $app->area_->url;
    }

    public function Osm_Core_App__query(App $app) {
        return Query::new();
    }

}