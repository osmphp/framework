<?php

namespace Osm\Data\Sheets;

use Osm\Framework\Data\ObjectRegistry;

class Sheets extends ObjectRegistry
{
    public $class_ = Sheet::class;
    public $config = 'sheets';
    public $not_found_message = "Sheet ':name' not found";
}