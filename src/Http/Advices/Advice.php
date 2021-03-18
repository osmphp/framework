<?php

declare(strict_types=1);

namespace Osm\Framework\Http\Advices;

use Osm\Core\App;
use Osm\Core\Exceptions\NotImplemented;
use Osm\Core\Object_;
use Osm\Framework\Http\Http;
use Symfony\Component\HttpFoundation\Response;

/**
 * @property Http $http
 */
class Advice extends Object_
{
    public function around(callable $next): Response {
        throw new NotImplemented();
    }

    /** @noinspection PhpUnused */
    protected function get_http(): Http {
        global $osm_app; /* @var App $osm_app */

        return $osm_app->http;
    }
}