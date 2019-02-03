<?php

namespace Manadev\Framework\Layers;

use Manadev\Core\Modules\BaseModule;
use Manadev\Core\Properties;

class Module extends BaseModule
{
    public $hard_dependencies = [
        'Manadev_Framework_Views',
        'Manadev_Framework_Settings',
        'Manadev_Framework_Logging',
    ];

    public $traits = [
        Properties::class => Traits\PropertiesTrait::class,
    ];
}