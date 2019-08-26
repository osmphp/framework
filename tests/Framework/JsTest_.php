<?php

namespace Osm\Tests\Framework;


use Osm\Framework\Testing\Browser\Browser;
use Osm\Framework\Testing\Tests\AppTestCase;

class JsTest_ extends AppTestCase
{
    public function testJs() {
        $this->browse('chrome', function(Browser $browser) {
            $html = $browser->html('GET /tests/api/ajax');
        });
    }
}