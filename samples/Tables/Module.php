<?php

namespace Osm\Samples\Tables;

use Osm\Data\TableQueries\Relations;
use Osm\Core\Modules\BaseModule;

class Module extends BaseModule
{
    public $hard_dependencies = [
        'Osm_Data_TableQueries',
    ];

    public $traits = [
        Relations::class => Traits\RelationsTrait::class,
    ];
}