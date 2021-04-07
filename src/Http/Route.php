<?php

declare(strict_types=1);

namespace Osm\Framework\Http;

use Osm\Core\App;
use Osm\Core\Exceptions\NotImplemented;
use Osm\Core\Object_;
use Symfony\Component\HttpFoundation\Response;

/**
 * @property Http $http
 */
class Route extends Object_
{
    public function run(): Response {
        throw new NotImplemented($this);
    }

    public function match(): ?Route {
        throw new NotImplemented($this);
    }

    /** @noinspection PhpUnused */
    protected function get_http(): Http {
        global $osm_app; /* @var App $osm_app */

        return $osm_app->http;
    }
}