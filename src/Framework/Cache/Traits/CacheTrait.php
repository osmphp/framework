<?php

namespace Osm\Framework\Cache\Traits;

use Osm\Core\App;
use Osm\Framework\Cache\ItemValidator;

trait CacheTrait
{
    protected function around_put(callable $proceed, ...$args) {
        global $osm_app; /* @var App $osm_app */

        /* @var ItemValidator $validator */
        $validator = $osm_app[ItemValidator::class];

        $validator->validateItem($args[1]);

        return $proceed(...$args);
    }
}