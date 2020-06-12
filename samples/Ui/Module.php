<?php

namespace Osm\Samples\Ui;

use Osm\Core\Modules\BaseModule;
use Osm\Data\TableQueries\Relations;

class Module extends BaseModule
{
    public $hard_dependencies = [
        'Osm_Ui_Tables',
        'Osm_Data_TableSheets',
        'Osm_Samples_Js',
    ];

    public $traits = [
        Relations::class => Traits\RelationsTrait::class,
    ];
}