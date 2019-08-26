<?php

use Osm\Ui\Menus\Items\Type;

return [
    Type::SEPARATOR => ['popup_menu_template' => 'Osm_Ui_PopupMenus.separator'],
    Type::PLACEHOLDER => ['popup_menu_template' => 'Osm_Ui_PopupMenus.placeholder'],
    Type::LABEL => ['popup_menu_template' => 'Osm_Ui_PopupMenus.label'],
    Type::SUBMENU => ['popup_menu_template' => 'Osm_Ui_PopupMenus.submenu'],
    Type::INPUT => ['popup_menu_template' => 'Osm_Ui_PopupMenus.input'],
    Type::COMMAND => ['popup_menu_template' => 'Osm_Ui_PopupMenus.command'],
    Type::LINK => ['popup_menu_template' => 'Osm_Ui_PopupMenus.link'],
];