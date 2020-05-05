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
class DelimiterItem extends Item
{
    public $menu_item_template = 'Osm_Ui_Menus.{menu_type}.delimiter';
    public $type = '-delimiter';
}