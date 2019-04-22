<?php

namespace Manadev\Ui\MenuBars;

use Manadev\Core\Modules\BaseModule;

class Module extends BaseModule
{
    public $hard_dependencies = [
        'Manadev_Ui_Menus',
        'Manadev_Ui_Buttons',
        'Manadev_Ui_PopupMenus',
    ];
}