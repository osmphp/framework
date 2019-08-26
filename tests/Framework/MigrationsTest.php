<?php

namespace Osm\Tests\Framework;

use Illuminate\Database\Schema\Blueprint;
use Osm\Core\App;
use Osm\Framework\Migrations\Migrator;
use Osm\Framework\Testing\Tests\UnitTestCase;

class MigrationsTest extends UnitTestCase
{
    public function test_basic_schema_operations() {
        global $osm_app; /* @var App $osm_app */

        $schema = $osm_app->db->schema;

        $schema->create('test_raw', function(Blueprint $table) {
            $table->increments('id');
        });

        $this->assertTrue($schema->hasTable('test_raw'));

        $schema->dropIfExists('test_raw');
    }

    public function test_raw_schema_migration() {
        global $osm_app; /* @var App $osm_app */

        $schema = $osm_app->db->schema;

        /* @var Migrator $migrator */
        $migrator = Migrator::new(['modules' => ['Osm_Samples_Migrations']]);

        $migrator->migrate();
        $this->assertTrue($schema->hasTable('t_raw'));

        $migrator->migrateBack();
        $this->assertFalse($schema->hasTable('t_raw'));
    }
}