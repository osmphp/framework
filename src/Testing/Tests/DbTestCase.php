<?php

namespace Osm\Framework\Testing\Tests;

use Osm\Core\App;
use Osm\Framework\Migrations\Migrator;
use Osm\Framework\Testing\DbTestSuite;

abstract class DbTestCase extends UnitTestCase
{
    public $suite = 'db';

    protected static $areDbTestsSetUp = false;

    protected function setUp(): void {
        global $osm_app; /* @var App $osm_app */

        parent::setUp();

        if (static::$areDbTestsSetUp) {
            return;
        }

        if (!env('NO_MIGRATE')) {
            /* @var DbTestSuite $suite_ */
            $suite_ = $osm_app->testing->suites[$this->suite];

            echo "php run migrate --fresh ; only basic modules\n";
            Migrator::new(['fresh' => true, 'modules' => $suite_->modules])->migrate();
        }

        static::$areDbTestsSetUp = true;
    }
}