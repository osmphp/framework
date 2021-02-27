<?php

declare(strict_types=1);

namespace Osm\Framework\Tests;

use Osm\Framework\Samples\App;
use Osm\Runtime\Apps;
use PHPUnit\Framework\TestCase;
use function Osm\__;

class test_04_translations extends TestCase
{
    public function test_externally_set_value() {
        Apps::run(Apps::create(App::class), function(App $app) {
            // GIVEN that PhpUnit sets APP_LOCALE=lt_LT

            // WHEN you request text translation
            $text = __("A random text ':text'", ['text' => "Hello, world"]);

            // THEN you get it according to the current locale
            $this->assertEquals("Atsitiktinis tekstas 'Hello, world'", $text);
        });
    }
}