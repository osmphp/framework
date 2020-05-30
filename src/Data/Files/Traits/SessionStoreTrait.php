<?php

namespace Osm\Data\Files\Traits;

use Osm\Core\App;
use Osm\Data\Files\Files;

trait SessionStoreTrait
{
    protected function around_gc(callable $proceed) {
        global $osm_app; /* @var App $osm_app */

        /* @var Files $files */
        $files = $osm_app[Files::class];

        $files->deleteExpiredSessionFiles($osm_app->area_);
    }
}