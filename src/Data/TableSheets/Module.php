<?php

namespace Osm\Data\TableSheets;

use Osm\Core\Modules\BaseModule;

class Module extends BaseModule
{
    public $hard_dependencies = [
        'Osm_Data_Sheets',
        'Osm_Data_TableQueries',
    ];
}