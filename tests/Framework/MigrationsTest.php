<?php

namespace Manadev\Tests\Framework;

use Illuminate\Database\Schema\Blueprint;
use Manadev\Core\App;
use Manadev\Framework\Migrations\Migrator;
use Manadev\Framework\Testing\Tests\UnitTestCase;

class MigrationsTest extends UnitTestCase
{
    public function test_basic_schema_operations() {
        global $m_app; /* @var App $m_app */

        $schema = $m_app->db->schema;

        $schema->create('test_raw', function(Blueprint $table) {
            $table->increments('id');
        });

        $this->assertTrue($schema->hasTable('test_raw'));

        $schema->dropIfExists('test_raw');
    }

    public function test_raw_schema_migration() {
        global $m_app; /* @var App $m_app */

        $schema = $m_app->db->schema;

        /* @var Migrator $migrator */
        $migrator = Migrator::new(['modules' => ['Manadev_Samples_Migrations']]);

        $migrator->migrate();
        $this->assertTrue($schema->hasTable('t_raw'));

        $migrator->migrateBack();
        $this->assertFalse($schema->hasTable('t_raw'));
    }
}