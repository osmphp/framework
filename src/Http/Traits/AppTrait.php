<?php

declare(strict_types=1);

namespace Osm\Framework\Http\Traits;

use Osm\Framework\Http\Http;

/**
 * @property Http $http
 */
trait AppTrait
{
    /** @noinspection PhpUnused */
    protected function get_http(): Http {
        return Http::new();
    }
}