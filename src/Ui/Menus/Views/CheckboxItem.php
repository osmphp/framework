<?php

namespace Osm\Ui\Menus\Views;

/**
 * @property string $title @required @part
 * @property bool $checked @part
 *
 * Keymap property:
 *
 * @property string $shortcut @part
 */
class CheckboxItem extends Item
{
    public $menu_item_template = 'Osm_Ui_Menus.{menu_type}.checkbox';
    public $menu_item_view_model = 'Osm_Ui_Menus.{menu_type}.CheckboxItem';
    public $type = '-checkbox';

    public function rendering() {
        $this->model = osm_merge([
            'checked' => $this->checked ?? false,
        ], $this->model ?: []);
    }
}