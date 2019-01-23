<?php

namespace Manadev\Framework\Installer;

use Manadev\Framework\Data\CollectionRegistry;

class Questions extends CollectionRegistry
{
    public $class_ = Question::class;
    public $config = 'installation_questions';
    public $not_found_message = "Installation question ':name' not found";
}