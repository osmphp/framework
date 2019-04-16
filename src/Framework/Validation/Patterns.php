<?php

namespace Manadev\Framework\Validation;

use Manadev\Framework\Data\CollectionRegistry;

class Patterns extends CollectionRegistry
{
    public $config = 'validation_patterns';
    public $not_found_message = "Validation pattern ':name' not found";
    public $class_ = Pattern::class;
}