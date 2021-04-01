<?php

declare(strict_types=1);

namespace Osm\Framework\Tests\Unit;

use Osm\Framework\Migrations\Module;
use Osm\Framework\Samples\App;
use Osm\Runtime\Apps;
use PHPUnit\Framework\TestCase;

class test_06_migrations extends TestCase
{
    public function test_migrations() {
        Apps::run(Apps::create(App::class), function(App $app) {
            // GIVEN that \Osm\Framework\Samples\Migrations\Module introduces
            // t_migrated table in its migrations

            // WHEN you run the migrations
            $app->migrations()->fresh();
            $app->migrations()->up();

            // THEN the data is indeed in the database
            $price = $app->db->table('t_migrated')
                ->where('sku', 'P1')
                ->value('price');
            $this->assertEquals(10.0, $price);

            // WHEN you migrate it back
            $app->migrations()->down(Module::class);

            // THEN the `t_products` table is gone
            $this->assertFalse($app->db->exists('t_migrated'));
        });
    }
}