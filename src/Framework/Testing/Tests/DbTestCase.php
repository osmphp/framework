<?php

namespace Manadev\Framework\Testing\Tests;

use Manadev\Core\App;
use Manadev\Framework\Migrations\Migrator;
use Manadev\Framework\Testing\DbTestSuite;

abstract class DbTestCase extends UnitTestCase
{
    public $suite = 'db_tests';

    protected static $areDbTestsSetUp = false;

    protected function setUp() {
        global $m_app; /* @var App $m_app */

        parent::setUp();

        if (static::$areDbTestsSetUp) {
            return;
        }

        if (!env('NO_MIGRATE')) {
            /* @var DbTestSuite $suite_ */
            $suite_ = $m_app->testing->suites[$this->suite];

            echo "php run migrate --fresh ; only basic modules\n";
            Migrator::new(['fresh' => true, 'modules' => $suite_->modules])->migrate();
        }

        static::$areDbTestsSetUp = true;
    }
}