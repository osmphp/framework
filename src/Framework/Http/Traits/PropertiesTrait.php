<?php

namespace Osm\Framework\Http\Traits;

use Osm\Core\App;
use Osm\Framework\Http\Query;
use Osm\Framework\Http\Request;
use Osm\Framework\Http\UrlGenerator;

trait PropertiesTrait
{
    public function Osm_Core_App__request(App $app) {
        return Request::new();
    }

    public function Osm_Core_App__url_generator(App $app) {
        return UrlGenerator::new();
    }

    public function Osm_Core_App__query(App $app) {
        return Query::new();
    }

}