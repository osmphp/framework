<?php

namespace Osm\Framework\WebPack;

use Osm\Core\Modules\BaseModule;

class Module extends BaseModule
{
    public $hard_dependencies = [
        'Osm_Framework_Npm',
    ];
}