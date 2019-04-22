<?php

namespace Manadev\Ui\Menus\Items;

use Manadev\Framework\Data\CollectionRegistry;

class Types extends CollectionRegistry
{
    public $class_ = Type::class;
    public $config = 'menu_item_types';
    public $not_found_message = "Menu item type ':name' not found";
}