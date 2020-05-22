<?php

namespace Osm\Ui\Menus\Views;

/**
 * @property string $title @required @part
 * @property string $accept @part
 * @property bool $multi_select @part
 *
 * Style properties:
 *
 * @property string $icon @part
 *
 * Keymap property:
 *
 * @property string $shortcut @part
 */
class UploadCommandItem extends Item
{
    public $menu_item_template = 'Osm_Ui_Menus.{menu_type}.upload_command';
    public $menu_item_view_model = 'Osm_Ui_Menus.{menu_type}.UploadCommandItem';
    public $type = '-upload-command';
}