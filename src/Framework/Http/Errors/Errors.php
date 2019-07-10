<?php

namespace Manadev\Framework\Http\Errors;

use Manadev\Framework\Data\CollectionRegistry;
use Manadev\Framework\Http\Exceptions\NotFound;

class Errors extends CollectionRegistry
{
    public $class_ = Error::class;
    public $config = 'http_errors';
    public $not_found_message = "HTTP error ':name' not found";
}