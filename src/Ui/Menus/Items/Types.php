<?php

namespace Osm\Ui\Menus\Items;

use Osm\Framework\Data\CollectionRegistry;

class Types extends CollectionRegistry
{
    public $class_ = Type::class;
    public $config = 'menu_item_types';
    public $not_found_message = "Menu item type ':name' not found";
}