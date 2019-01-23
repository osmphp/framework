<?php

namespace Manadev\Framework\Data;

use Manadev\Core\Modules\BaseModule;

class Module extends BaseModule
{
    public $hard_dependencies = [
        'Manadev_Framework_Cache',
        'Manadev_Framework_Localization',
    ];
}