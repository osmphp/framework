<?php

declare(strict_types=1);

namespace Osm\Framework\Tests\Unit;

use Osm\Framework\Http\Client;
use Osm\Framework\Samples\App;
use Osm\Runtime\Apps;
use PHPUnit\Framework\TestCase;

class test_09_http extends TestCase
{
    public function test_internal_browser() {
        Apps::run(Apps::create(App::class), function(App $app) {
            // GIVEN an app with a `GET /test` route and a browser
            $client = new Client();

            // WHEN you browse the route
            $text = $client->request('GET', '/test')
                ->filter('.test')
                ->text();

            // THEN its output is fetched
            $this->assertEquals('Hi', $text);
        });
    }

    public function test_blade() {
        Apps::run(Apps::create(App::class), function(App $app) {
            // GIVEN an app with a `GET /test` route and a browser
            $client = new Client();

            // WHEN you browse the route
            $text = $client->request('GET', '/test-blade')
                ->filter('.test')
                ->text();

            // THEN its output is fetched
            $this->assertEquals('Hi', $text);
        });
    }

    public function test_components() {
        Apps::run(Apps::create(App::class), function(App $app) {
            // GIVEN an app with a `GET /test` route and a browser
            $client = new Client();

            // WHEN you browse the route
            $text = $client->request('GET', '/test-components')
                ->filter('.test')
                ->text();

            // THEN its output is fetched
            $this->assertEquals('Hi', $text);
        });
    }
}