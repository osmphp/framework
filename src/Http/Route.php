<?php

declare(strict_types=1);

namespace Osm\Framework\Http;

use Osm\Core\Exceptions\NotImplemented;
use Osm\Core\Object_;
use Symfony\Component\HttpFoundation\Response;

class Route extends Object_
{
    public function run(): Response {
        throw new NotImplemented();
    }
}