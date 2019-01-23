<?php

namespace Manadev\Framework\Encryption\Hashing;

use Manadev\Framework\Data\CollectionRegistry;

class Hashings extends CollectionRegistry
{
    public $class_ = Hashing::class;
    public $config = 'hashings';
    public $not_found_message = "Hashing ':name' not found";
}