<?php

namespace Osm\Ui\Menus\Items;

use Osm\Core\App;
use Osm\Core\Object_;
use Osm\Core\Promise;
use Osm\Ui\Menus\Module;
use Osm\Ui\Menus\Views\Menu;

/**
 * Dependencies:
 *      @property Module $module @required
 *      @property Type $type_ @required
 * Basic menu item properties:
 *      @property Menu $parent @required
 *      @property string $name @part
 *      @property string $type @required @part
 *      @property string $modifier @part // CSS modifier
 *      @property int $sort_order @part
 *      @property bool $deleted @part
 * Named menu items may have title and icon:
 *      @property string $title @required @part
 *      @property string $icon @part
 * Interactive menu items may be enabled disabled, checked/unchecked, may belong to checkbox group:
 *      @property bool $disabled @part
 *      @property bool $checked @part
 *      @property string $checkbox_group @part
 * Some items may have keyboard shortcuts:
 *      @property string $shortcut @part
 * Links (only) have URL to navigate to when pressed:
 *      @property string|Promise $url @required @part
 * Sub-menus (only) may have list of child menu items
 *      @property array $items @required @part
 */
class Item extends Object_
{
    protected function default($property) {
        global $osm_app; /* @var App $osm_app */

        switch ($property) {
            case 'module': return $osm_app->modules['Osm_Ui_Menus'];
            case 'type_': return $this->module->item_types[$this->type];
        }
        return parent::default($property);
    }
}