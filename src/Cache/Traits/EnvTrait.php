<?php

declare(strict_types=1);

namespace Osm\Framework\Cache\Traits;

use Osm\Core\App;
use Osm\Framework\Cache\File;

/**
 * @property string $CACHE_CLASS_NAME
 * @property string $CACHE_PATH
 */
trait EnvTrait
{
    /** @noinspection PhpUnused */
    protected function get_CACHE_CLASS_NAME(): string {
        return File::class;
    }

    /** @noinspection PhpUnused */
    protected function get_CACHE_PATH(): string {
        global $osm_app; /* @var App $osm_app */

        return "{$osm_app->paths->temp}/cache";
    }
}