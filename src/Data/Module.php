<?php

namespace Osm\Framework\Data;

use Osm\Core\Modules\BaseModule;

class Module extends BaseModule
{
    public $hard_dependencies = [
        'Osm_Framework_Cache',
        'Osm_Framework_Localization',
    ];
}