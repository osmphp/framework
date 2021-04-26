<?php

declare(strict_types=1);

namespace Osm\Framework\Tests\Unit;

use Osm\Framework\TestCase;

class test_09_http extends TestCase
{
    public string $app_class_name = \Osm\Framework\Samples\App::class;
    public bool $use_http = true;

    public function test_internal_browser() {
        // GIVEN an app with a `GET /test` route and a browser

        // WHEN you browse the route
        $text = $this->http->request('GET', '/test')
            ->filter('.test')
            ->text();

        // THEN its output is fetched
        $this->assertEquals('Hi', $text);
    }

    public function test_blade() {
        // GIVEN an app with a `GET /test` route and a browser

        // WHEN you browse the route
        $text = $this->http->request('GET', '/test-blade')
            ->filter('.test')
            ->text();

        // THEN its output is fetched
        $this->assertEquals('Hi', $text);
    }

    public function test_components() {
        // GIVEN an app with a `GET /test` route and a browser

        // WHEN you browse the route
        $text = $this->http->request('GET', '/test-components')
            ->filter('.test')
            ->text();

        // THEN its output is fetched
        $this->assertEquals('Hi', $text);
    }
}