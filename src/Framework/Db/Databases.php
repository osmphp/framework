<?php

namespace Osm\Framework\Db;

use Osm\Framework\Data\CollectionRegistry;

class Databases extends CollectionRegistry
{
    public $class_ = Db::class;
    public $config = 'db';
    public $not_found_message = "Database ':name' not found";
}