<?php

namespace Osm\Data\TableQueries\Traits;

use Osm\Core\App;
use Osm\Data\Formulas\Functions\Functions;

trait PropertiesTrait
{
    public function Osm_Core_App__table_functions(App $app) {
        return $app->cache->remember("table_functions", function($data) {
            return Functions::new(array_merge(['config' => 'table_functions'], $data));
        });
    }
}