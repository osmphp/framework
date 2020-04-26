<?php

namespace Osm\Data\Files;

use Osm\Core\Modules\BaseModule;

class Module extends BaseModule
{
    public $hard_dependencies = [
        'Osm_Data_Urls',
    ];
}