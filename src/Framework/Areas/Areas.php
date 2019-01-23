<?php

namespace Manadev\Framework\Areas;

use Manadev\Framework\Data\CollectionRegistry;

class Areas extends CollectionRegistry
{
    public $class_ = Area::class;
    public $config = 'areas';
    public $not_found_message = "Area ':name' not found";

}