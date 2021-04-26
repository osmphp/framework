<?php

declare(strict_types=1);

namespace Osm\Framework\Tests\Unit;

use Osm\Core\TestCase;
use function Osm\__;

class test_04_translations extends TestCase
{
    public string $app_class_name = \Osm\Framework\Samples\App::class;

    public function test_externally_set_value() {
        // GIVEN that PhpUnit sets $osm_app->settings->locale=lt_LT

        // WHEN you request text translation
        $text = __("A random text ':text'", ['text' => "Hello, world"]);

        // THEN you get it according to the current locale
        $this->assertEquals("Atsitiktinis tekstas 'Hello, world'", $text);
    }
}