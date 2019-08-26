<?php

namespace Osm\Tests\Core;

use Osm\Core\App;
use Osm\Core\Object_;
use Osm\Framework\Testing\Tests\UnitTestCase;

class AppTest extends UnitTestCase
{
    public function test_that_tests_are_executed() {
        $this->assertTrue(true);
    }

    public function test_that_app_is_accessible() {
        global $m_app; /* @var App $m_app */

        $this->assertNotNull($m_app);
    }

    public function test_that_environment_is_loaded() {
        $this->assertEquals('testing', getenv('APP_ENV'));
    }

    public function test_that_packages_are_loaded() {
        global $m_app; /* @var App $m_app */

        $this->assertArrayHasKey('dubysa/framework', $m_app->packages);
    }

    public function test_that_modules_are_loaded() {
        global $m_app; /* @var App $m_app */

        $this->assertArrayHasKey('Osm_Framework_Testing', $m_app->modules);
    }
}