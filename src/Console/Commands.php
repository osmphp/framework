<?php

namespace Osm\Framework\Console;

use Osm\Framework\Data\CollectionRegistry;

class Commands extends CollectionRegistry
{
    public $class_ = Command::class;
    public $config = 'console_commands';
    public $not_found_message = "Console command ':name' not found";
}