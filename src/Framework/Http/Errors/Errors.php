<?php

namespace Osm\Framework\Http\Errors;

use Osm\Framework\Data\CollectionRegistry;
use Osm\Framework\Http\Exceptions\NotFound;

class Errors extends CollectionRegistry
{
    public $class_ = Error::class;
    public $config = 'http_errors';
    public $not_found_message = "HTTP error ':name' not found";
}