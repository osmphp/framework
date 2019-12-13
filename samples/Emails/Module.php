<?php

namespace Osm\Samples\Emails;

use Osm\Core\Modules\BaseModule;

class Module extends BaseModule
{
    public $hard_dependencies = [
        'Osm_Samples_Layers',
    ];
}