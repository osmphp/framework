<?php

namespace Osm\Data\Urls;

use Osm\Core\Modules\BaseModule;

class Module extends BaseModule
{
    public $hard_dependencies = [
        'Osm_Data_Indexing',
    ];
}