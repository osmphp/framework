<?php

namespace Osm\Data\TableQueries\Functions;

use Osm\Core\App;
use Osm\Data\Queries\Functions\Resolver as BaseFunctionResolver;

class Resolver extends BaseFunctionResolver
{
    protected function default($property) {
        global $osm_app; /* @var App $osm_app */

        switch ($property) {
            case 'functions': return $osm_app->table_functions;
        }
        return parent::default($property);
    }
}