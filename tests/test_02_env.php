<?php

declare(strict_types=1);

namespace Osm\Framework\Tests;

use Osm\Framework\Console\Module;
use Osm\Framework\Samples\App;
use Osm\Runtime\Apps;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Input\StringInput;
use Symfony\Component\Console\Output\BufferedOutput;

class test_02_env extends TestCase
{
    public function test_externally_set_value() {
        Apps::run(Apps::create(App::class), function(App $app) {
            // GIVEN that PhpUnit configuration set a APP_LOCALE
            // environment variable

            // WHEN you access it
            $locale = $app->env->APP_LOCALE;

            // THEN it is as set in PhpUnit configuration
            $this->assertEquals('lt_LT', $locale);
        });
    }
}