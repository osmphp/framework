<?php

namespace Osm\Framework\Sessions;

use Osm\Framework\Data\CollectionRegistry;
use Osm\Framework\Sessions\Stores\Store;

class Stores extends CollectionRegistry
{
    public $class_ = Store::class;
    public $config = 'session_stores';
    public $not_found_message = "Session store ':name' not found";
}