<?php

namespace Osm\Framework\Areas;

use Osm\Framework\Data\CollectionRegistry;

class Areas extends CollectionRegistry
{
    public $class_ = Area::class;
    public $config = 'areas';
    public $not_found_message = "Area ':name' not found";

}