<?php

namespace Manadev\Data\TableQueries;

use Manadev\Core\App;
use Manadev\Core\Properties;
use Manadev\Data\Formulas\Functions\Functions;
use Manadev\Core\Modules\BaseModule;
use Manadev\Framework\Db\MySql;

class Module extends BaseModule
{
    public $hard_dependencies = [
        'Manadev_Data_Queries',
        'Manadev_Data_Tables',
    ];

    public $traits = [
        Properties::class => Traits\PropertiesTrait::class,
        MySql::class => Traits\MySqlTrait::class,
    ];
}