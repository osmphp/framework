<?php

namespace Osm\Framework\Cache;

use Osm\Core\App;
use Osm\Core\Modules\BaseModule;
use Osm\Core\Properties;

class Module extends BaseModule
{
    public $traits = [
        Properties::class => Traits\PropertiesTrait::class,
    ];

    public function terminate() {
        global $osm_app; /* @var App $osm_app */

        $osm_app->caches->terminate();

        parent::terminate();
    }
}