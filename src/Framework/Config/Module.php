<?php

namespace Manadev\Framework\Config;

use Manadev\Core\Modules\BaseModule;

class Module extends BaseModule
{
    public $hard_dependencies = [
        'Manadev_Framework_Console',
    ];
}