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
    public $menu_item_template = 'Osm_Ui_Menus.{menu_type}.link';
    public $menu_item_view_model = 'Osm_Ui_Menus.{menu_type}.LinkItem';
    public $type = '-link';
}