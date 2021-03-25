<?php

declare(strict_types=1);

namespace Osm\Framework\Samples\Http\Routes\Front;

use Osm\Core\Attributes\Name;
use Osm\Framework\Areas\Attributes\Area;
use Osm\Framework\Areas\Front;
use Osm\Framework\Http\Route;
use Symfony\Component\HttpFoundation\Response;
use function Osm\view;

#[Area(Front::class), Name('GET /test-blade')]
class TestBlade extends Route
{
    public function run(): Response {
        return new Response((string)
            view('sample-http::test-blade'));
    }
}