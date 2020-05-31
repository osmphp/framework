<?php

namespace Osm\Data\Files;

use Osm\Core\Modules\BaseModule;
use Osm\Framework\Migrations\Migrator;
use Osm\Framework\Sessions\Stores\Store;

class Module extends BaseModule
{
    public $hard_dependencies = [
        'Osm_Data_Tables',
        'Osm_Framework_Sessions',
    ];

    public $traits = [
        Store::class => Traits\SessionStoreTrait::class,
        Migrator::class => Traits\MigratorTrait::class,
    ];
}