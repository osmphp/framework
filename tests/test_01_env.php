<?php

declare(strict_types=1);

namespace Osm\Framework\Tests;

use Osm\Framework\Samples\App;
use Osm\Runtime\Apps;
use PHPUnit\Framework\TestCase;

class test_01_env extends TestCase
{
    public function test_externally_set_value() {
        Apps::run(Apps::create(App::class), function(App $app) {
            // GIVEN that PhpUnit configuration set a LOCALE
            // environment variable

            // WHEN you access it
            // THEN it is as set in PhpUnit configuration
            $this->assertEquals('test_value', $_ENV['TEST_VAR']);
        });
    }
}