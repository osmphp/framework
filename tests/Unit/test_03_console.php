<?php

declare(strict_types=1);

namespace Osm\Framework\Tests\Unit;

use Osm\Core\TestCase;
use Symfony\Component\Console\Input\StringInput;
use Symfony\Component\Console\Output\BufferedOutput;

class test_03_console extends TestCase
{
    public string $app_class_name = \Osm\Framework\Samples\App::class;

    public function test_arguments_and_options() {
        // GIVEN an app with a `hello` command defined in it

        // WHEN you run a command
        $this->app->console->setCatchExceptions(false);
        $this->app->console->setAutoExit(false);
        $this->app->console->run(new StringInput('hello --caps vo'),
            $output = new BufferedOutput());

        // THEN its output is fetched
        $this->assertEquals("Hello, VO\n", $output->fetch());
    }
}