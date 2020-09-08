<?php

namespace Osm\Framework\Cron;

use Osm\Core\App;
use Osm\Core\Modules\BaseModule;
use Osm\Core\Properties;

class Module extends BaseModule
{
    public $hard_dependencies = [
        'Osm_Framework_Logging',
    ];

    public $traits = [
        Properties::class => Traits\PropertiesTrait::class,
    ];
}