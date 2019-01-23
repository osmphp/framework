<?php

namespace Manadev\Framework\Testing\Tests;

use Manadev\Core\App;
use Manadev\Core\Object_;
use Manadev\Framework\Processes\Process;
use Manadev\Framework\Testing\TestCase;

abstract class UnitTestCase extends TestCase
{
    public $suite = 'unit_tests';
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