<?php

namespace Osm\Framework\Testing\Tests;

use Osm\Core\App;
use Osm\Core\Object_;
use Osm\Framework\Processes\Process;
use Osm\Framework\Testing\TestCase;

abstract class UnitTestCase extends TestCase
{
    public $suite = 'unit';
    protected static $areUnitTestsSetUp = false;

    protected function setUp() {
        if (static::$areUnitTestsSetUp) {
            return;
        }

        if (!env('NO_FRESH')) {
            echo "php fresh\n";
            Process::runInConsole('php fresh');
        }

        // boot application instance to be used in testing
        if (!static::$app_instance) {
            $this->recreateApp();
        }

        static::$areUnitTestsSetUp = true;
    }
}