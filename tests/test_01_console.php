<?php

declare(strict_types=1);

namespace Osm\Framework\Tests;

use Osm\Framework\Console\Module;
use Osm\Framework\Samples\App;
use Osm\Runtime\Apps;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Input\StringInput;
use Symfony\Component\Console\Output\BufferedOutput;

class test_01_console extends TestCase
{
    public function test_arguments_and_options() {
        Apps::run(Apps::create(App::class), function(App $app) {
            // GIVEN an app with a `hello` command defined in it
            $console = $app->modules[Module::class]; /* @var Module $console */

            // WHEN you run a command
            $console->symfony->setCatchExceptions(false);
            $console->symfony->setAutoExit(false);
            $console->symfony->run(
                new StringInput('hello --caps vo'),
                $output = new BufferedOutput());

            // THEN its output is fetched
            $this->assertEquals("Hello, VO\n", $output->fetch());
        });
    }
}