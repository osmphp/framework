<?php

namespace Osm\Framework\Composer;

use Osm\Framework\Data\CollectionRegistry;

class Hooks extends CollectionRegistry
{
    public $class_ = Hook::class;
    public $config = 'composer_hooks';
    public $not_found_message = "Composer hook ':name' not found";
}