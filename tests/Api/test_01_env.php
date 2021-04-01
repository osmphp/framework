<?php

declare(strict_types=1);

namespace Osm\Framework\Tests\Api;

use Osm\Framework\Samples\App;
use Osm\Runtime\Apps;
use PHPUnit\Framework\TestCase;

class test_01_env extends TestCase
{
    public function test_externally_set_value() {
        Apps::run(Apps::create(App::class), function(App $app) {
            // GIVEN

            // WHEN
            // THEN
            $this->assertTrue(true);
        });
    }
}