<?php

namespace Osm\Ui\Menus\Views;

class PopupMenu extends Menu
{
    public $type = 'popup_menu';
    public $template = 'Osm_Ui_Menus.popup_menu.menu';
    public $view_model = 'Osm_Ui_Menus.PopupMenu.Menu';

    protected function default($property) {
        switch ($property) {
            case 'on_color': return $this->getOnColor();
        }
        return parent::default($property);
    }

    protected function getOnColor() {
        for ($parent = $this->parent; $parent; $parent = $parent->parent) {
            if ($color = $parent->color ?: $parent->on_color) {
                return $color;
            }
        }

        return 'primary';
    }
}