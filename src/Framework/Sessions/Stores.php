<?php

namespace Manadev\Framework\Sessions;

use Manadev\Framework\Data\CollectionRegistry;
use Manadev\Framework\Sessions\Stores\Store;

class Stores extends CollectionRegistry
{
    public $class_ = Store::class;
    public $config = 'session_stores';
    public $not_found_message = "Session store ':name' not found";
}