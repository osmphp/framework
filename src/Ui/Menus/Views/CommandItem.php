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
    public $template = 'Osm_Ui_Menus.popup_menu.command';
    public $type = '-command';
}