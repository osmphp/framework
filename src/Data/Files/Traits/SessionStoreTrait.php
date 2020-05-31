<?php

namespace Osm\Data\Files\Traits;

use Osm\Core\App;
use Osm\Data\Files\GarbageCollector;

trait SessionStoreTrait
{
    protected function around_gc(callable $proceed) {
        global $osm_app; /* @var App $osm_app */

        $proceed();

        GarbageCollector::new()
            ->collectExpiredSessionFilesInArea($osm_app->area_);
    }
}