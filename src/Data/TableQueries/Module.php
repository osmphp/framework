<?php

namespace Osm\Data\TableQueries;

use Osm\Core\App;
use Osm\Core\Properties;
use Osm\Data\Formulas\Functions\Functions;
use Osm\Core\Modules\BaseModule;
use Osm\Framework\Db\MySql;

class Module extends BaseModule
{
    public $hard_dependencies = [
        'Osm_Data_Queries',
        'Osm_Data_Tables',
    ];

    public $traits = [
        Properties::class => Traits\PropertiesTrait::class,
        MySql::class => Traits\MySqlTrait::class,
    ];
}