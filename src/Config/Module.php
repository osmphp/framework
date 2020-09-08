<?php

namespace Osm\Framework\Config;

use Osm\Core\Modules\BaseModule;

class Module extends BaseModule
{
    public $hard_dependencies = [
        'Osm_Framework_Console',
    ];
}