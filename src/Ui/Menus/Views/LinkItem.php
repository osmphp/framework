<?php

namespace Osm\Ui\Menus\Views;

use Osm\Core\Promise;

/**
 * @property string $title @required @part
 * @property string|Promise $url @required @part
 *
 * Style properties:
 *
 * @property string $icon @part
 */
class LinkItem extends Item
{
    public $template = 'Osm_Ui_Menus.popup_menu.link';
    public $view_model = 'Osm_Ui_Menus.PopupMenu.LinkItem';
    public $type = '-link';
}