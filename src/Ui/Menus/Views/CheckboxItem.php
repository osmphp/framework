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
    public $checked_button_style = '-filled';
    public $unchecked_button_style = '-outlined';

    public function rendering() {
        $this->model = osm_merge([
            'checked' => $this->checked ?? false,
            'checked_button_style' => $this->checked_button_style,
            'unchecked_button_style' => $this->unchecked_button_style,
        ], $this->model ?: []);
    }
}