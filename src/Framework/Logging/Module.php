<?php

namespace Manadev\Framework\Logging;

use Manadev\Core\Modules\BaseModule;
use Manadev\Core\Properties;

class Module extends BaseModule
{
    public $traits = [
        Properties::class => Traits\PropertiesTrait::class,
    ];
}