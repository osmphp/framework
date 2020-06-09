<?php

namespace Osm\Samples\Ui;

use Osm\Core\Modules\BaseModule;

class Module extends BaseModule
{
    public $hard_dependencies = [
        'Osm_Ui_Tables',
        'Osm_Data_TableSheets',
        'Osm_Samples_Js',
    ];
}