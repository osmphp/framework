<?php

namespace Osm\Ui\Forms;

use Osm\Core\Modules\BaseModule;

class Module extends BaseModule
{
    public $hard_dependencies = [
        'Osm_Data_Sheets',
        'Osm_Ui_Aba',
        'Osm_Ui_SnackBars',
        'Osm_Ui_Menus',
        'Osm_Ui_Pages',
    ];
}