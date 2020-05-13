<?php

namespace Osm\Ui\Menus\Views;

/**
 * @property string $title @required @part
 * @property Item[] $items @required @part
 * @property PopupMenu $submenu @required
 *
 * Style properties:
 *
 * @property string $icon @part
 */
class SubmenuItem extends Item
{
    public $menu_item_template = 'Osm_Ui_Menus.{menu_type}.submenu';
    public $menu_item_view_model = 'Osm_Ui_Menus.{menu_type}.SubmenuItem';
    public $type = '-submenu';

    public function rendering() {
        $this->submenu = PopupMenu::new([
            'alias' => 'submenu',
            'on_color' => $this->parent->color ?: $this->parent->on_color,
            'items' => $this->items,
        ]);

        parent::rendering();
    }

}