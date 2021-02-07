<?php

declare(strict_types=1);

namespace Osm\Framework\Tests;

use Osm\Framework\Samples\App;
use Osm\Runtime\Apps;
use PHPUnit\Framework\TestCase;

class test_01_console extends TestCase
{
    public function test_something() {
        Apps::run(Apps::create(App::class), function() {
            // GIVEN a compiler configured to compile a sample app
            // WHEN you access a package,
            // THEN its information can be found in its properties
            $this->assertTrue(true);
        });
    }
}