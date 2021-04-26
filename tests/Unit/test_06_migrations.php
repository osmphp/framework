<?php

declare(strict_types=1);

namespace Osm\Framework\Tests\Unit;

use Osm\Core\TestCase;
use Osm\Framework\Migrations\Module;

class test_06_migrations extends TestCase
{
    public string $app_class_name = \Osm\Framework\Samples\App::class;

    public function test_migrations() {
        // GIVEN that \Osm\Framework\Samples\Migrations\Module introduces
        // t_migrated table in its migrations

        // WHEN you run the migrations
        $this->app->migrations()->fresh();
        $this->app->migrations()->up(
            \Osm\Framework\Samples\Migrations\Module::class);

        // THEN the data is indeed in the database
        $price = $this->app->db->table('t_migrated')
            ->where('sku', 'P1')
            ->value('price');
        $this->assertEquals(10.0, $price);

        // WHEN you migrate it back
        $this->app->migrations()->down(Module::class);

        // THEN the `t_products` table is gone
        $this->assertFalse($this->app->db->exists('t_migrated'));
    }
}