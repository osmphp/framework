<?php

declare(strict_types=1);

namespace Osm\Framework\Tests\Unit;

use Osm\Core\TestCase;

class test_01_env extends TestCase
{
    public string $app_class_name = \Osm\Framework\Samples\App::class;

    public function test_externally_set_value() {
        // GIVEN that PhpUnit configuration set a LOCALE
        // environment variable

        // WHEN you access it
        // THEN it is as set in PhpUnit configuration
        $this->assertEquals('test_value', $_ENV['TEST_VAR']);
    }
}