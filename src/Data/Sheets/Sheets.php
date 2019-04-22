<?php

namespace Manadev\Data\Sheets;

use Manadev\Framework\Data\ObjectRegistry;

class Sheets extends ObjectRegistry
{
    public $class_ = Sheet::class;
    public $config = 'sheets';
    public $not_found_message = "Sheet ':name' not found";
}