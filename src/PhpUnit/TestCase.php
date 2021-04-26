<?php

declare(strict_types=1);

namespace Osm\Framework\PhpUnit;

use Osm\Core\App;
use Osm\Runtime\Apps;
use PHPUnit\Framework\TestCase as BaseTestCase;

/**
 * @property string $app_class_name
 * @property App $app
 */
class TestCase extends BaseTestCase
{
    protected function setUp(): void {
        $this->app = Apps::create($this->app_class_name);
        Apps::enter($this->app);
        $this->app->boot();
    }

    protected function tearDown(): void {
        $this->app->terminate();
        Apps::leave();
        $this->app = null;
    }
}