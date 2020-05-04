<?php

namespace Osm\Ui\Menus\Views;

/**
 * @property string $title @required @part
 *
 * Style properties:
 *
 * @property string $icon @part
 *
 * Keymap property:
 *
 * @property string $shortcut @part
 */
class CommandItem extends Item
{
    public $menu_item_template = 'Osm_Ui_Menus.{menu_type}.command';
    public $menu_item_view_model = 'Osm_Ui_Menus.{menu_type}.CommandItem';
    public $type = '-command';
}