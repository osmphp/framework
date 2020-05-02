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
    public $template = 'Osm_Ui_Menus.popup_menu.checkbox';
    public $view_model = 'Osm_Ui_Menus.PopupMenu.CheckboxItem';
    public $type = '-checkbox';

    public function rendering() {
        $this->model = osm_merge([
            'checked' => $this->checked ?? false,
        ], $this->model ?: []);
    }
}