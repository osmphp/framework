<?php

declare(strict_types=1);

namespace Osm\Framework\Samples\Products\Routes\Api\Columns;

use Osm\Core\Attributes\Name;
use Osm\Framework\Areas\Api;
use Osm\Framework\Areas\Attributes\Area;
use Osm\Framework\Http\Route;
use Symfony\Component\HttpFoundation\Response;

#[Area(Api::class), Name('POST /products/columns')]
class Update extends Route
{
    public function run(): Response {
        return new Response((string)json_encode("ok"));
    }
}