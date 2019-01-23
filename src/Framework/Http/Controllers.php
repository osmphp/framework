<?php

namespace Manadev\Framework\Http;

use Manadev\Framework\Areas\AreaObjectRegistry;

class Controllers extends AreaObjectRegistry
{
    public $class_ = Controller::class;
    public $config = 'routes';
    public $not_found_message = "Controller ':name' not found";
}