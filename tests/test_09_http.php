<?php

declare(strict_types=1);

namespace Osm\Framework\Tests;

use Osm\Framework\Samples\App;
use Osm\Runtime\Apps;
use PHPUnit\Framework\TestCase;
use function Osm\browse;

class test_09_http extends TestCase
{
    public function test_arguments_and_options() {
        Apps::run(Apps::create(App::class), function(App $app) {
            // GIVEN an app with a `GET /test` route and a browser
            $browser = browse();

            // WHEN you browse the route
            $text = $browser->get('/test')
                ->filter('.test')
                ->text();

            // THEN its output is fetched
            $this->assertEquals('Hi', $text);
        });
    }
}