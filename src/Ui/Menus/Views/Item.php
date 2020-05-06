<?php

namespace Osm\Ui\Menus\Views;

use Osm\Framework\Views\View;

/**
 * @property string $type @required @part
 *
 * Style properties:
 *
 * @property bool $main @part
 * @property bool $dangerous @part
 *
 * More precise type spec:
 *
 * @property Menu $parent
 *
 * Properties for menu bar rendering:
 *
 * @property string $menu_item_template @required @part
 * @property string $menu_item_view_model @part
 */
abstract class Item extends View
{
    protected function default($property) {
        switch ($property) {
            case 'template':
                return str_replace('{menu_type}', $this->parent->type,
                    $this->menu_item_template);
            case 'view_model':
                return str_replace('{menu_type}',
                    studly_case($this->parent->type),
                    $this->menu_item_view_model);
        }
        return parent::default($property);
    }
}