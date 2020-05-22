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
            case 'color': return 'neutral';
        }
        return parent::default($property);
    }

    protected function getOnColor() {
        if (($result = $this->parent->on_color ?? 'neutral') != 'neutral') {
            return $result;
        }

        if (($result = $this->parent->color ?? 'neutral') != 'neutral') {
            return $result;
        }

        return 'primary';
    }
}