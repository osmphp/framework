<?php

namespace Manadev\Samples\Tables;

use Manadev\Data\TableQueries\Relations;
use Manadev\Core\Modules\BaseModule;

class Module extends BaseModule
{
    public $hard_dependencies = [
        'Manadev_Data_TableQueries',
    ];

    public $traits = [
        Relations::class => Traits\RelationsTrait::class,
    ];
}