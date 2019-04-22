<?php

namespace Manadev\Data\TableQueries\Traits;

use Manadev\Core\App;
use Manadev\Data\Formulas\Functions\Functions;

trait PropertiesTrait
{
    public function Manadev_Core_App__table_functions(App $app) {
        return $app->cache->remember("table_functions", function($data) {
            return Functions::new(array_merge(['config' => 'table_functions'], $data));
        });
    }
}