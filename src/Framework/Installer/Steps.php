<?php

namespace Manadev\Framework\Installer;

use Manadev\Framework\Data\CollectionRegistry;

class Steps extends CollectionRegistry
{
    public $class_ = Step::class;
    public $config = 'installation_steps';
    public $not_found_message = "Installation step ':name' not found";
}