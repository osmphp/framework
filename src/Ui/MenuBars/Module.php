<?php

namespace Osm\Ui\MenuBars;

use Osm\Core\Modules\BaseModule;

class Module extends BaseModule
{
    public $hard_dependencies = [
        'Osm_Ui_Menus',
        'Osm_Ui_Buttons',
        'Osm_Ui_PopupMenus',
    ];
}