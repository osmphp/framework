<?php

namespace Manadev\Data\Indexing;

use Manadev\Framework\Data\ObjectRegistry;

class Targets extends ObjectRegistry
{
    public $class_ = Target::class;
    public $config = 'indexers';
    public $not_found_message = "Index target ':name' not found";
}