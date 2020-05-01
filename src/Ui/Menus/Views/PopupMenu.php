<?php

namespace Osm\Ui\Menus\Views;

use Osm\Framework\Views\Views\Container;

/**
 * @property Item[] $items_ @required
 */
class PopupMenu extends Container
{
    public $template = 'Osm_Ui_Menus.popup_menu.menu';
    public $view_model = 'Osm_Ui_Menus.PopupMenu';
}