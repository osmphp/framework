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
        global $m_app; /* @var App $m_app */

        $m_app->caches->terminate();

        parent::terminate();
    }
}