<?php

namespace Osm\Framework\Encryption\Hashing;

use Osm\Framework\Data\CollectionRegistry;

class Hashings extends CollectionRegistry
{
    public $class_ = Hashing::class;
    public $config = 'hashings';
    public $not_found_message = "Hashing ':name' not found";
}