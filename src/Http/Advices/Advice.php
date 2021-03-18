<?php

declare(strict_types=1);

namespace Osm\Framework\Http\Advices;

use Osm\Core\App;
use Osm\Core\Object_;
use Osm\Framework\Http\Http;

/**
 * @property Http $http
 */
class Advice extends Object_
{
    public function around(callable $next) {
        return $next();
    }

    /** @noinspection PhpUnused */
    protected function get_http(): Http {
        global $osm_app; /* @var App $osm_app */

        return $osm_app->http;
    }
}