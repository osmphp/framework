<?php

namespace Osm\Framework\Cache\Traits;

use Osm\Core\App;
use Osm\Core\Profiler;
use Osm\Framework\Cache\ItemValidator;

trait CacheTrait
{
    protected function around_put(callable $proceed, ...$args) {
        global $osm_app; /* @var App $osm_app */
        global $osm_profiler; /* @var Profiler $osm_profiler */

        if ($osm_profiler) $osm_profiler->start('parent_validation', 'cache');
        try {
            /* @var ItemValidator $validator */
            $validator = $osm_app[ItemValidator::class];

            $validator->validateItem($args[1]);

            return $proceed(...$args);
        }
        finally {
            if ($osm_profiler) $osm_profiler->stop('parent_validation');
        }
    }
}