<?php

namespace Manadev\Tests\Data;

use Manadev\Core\App;
use Manadev\Data\Tables\Blueprint;
use Manadev\Framework\Testing\Tests\DbTestCase;

class TablesTest extends DbTestCase
{
    public function test_create_alter_and_drop() {
        global $m_app; /* @var App $m_app */

        $db = $m_app->db;

        $db->create('test_table', function (Blueprint $table) {
            $table->string('sku')->title("SKU")->required()->unique();
            $table->string('title')->title("Title")->partition(2);
        });

        try {
            $this->assertTrue($db->schema->hasTable('test_table'));
            $this->assertTrue($db->schema->hasTable('test_table__2'));

            $this->assertNotNull($db->connection->table('tables')
                ->where('name', '=', 'test_table')
                ->value('id'));
            $this->assertEquals(0, $db->connection->table('table_columns AS column')
                ->join('tables AS table', 'table.id', '=', 'column.table')
                ->where('table.name', '=', 'test_table')
                ->where('column.name', '=', 'title')
                ->value('column.required'));

            $this->assertTrue(isset($db->tables['test_table']));
            $this->assertTrue(isset($db->tables['test_table']->columns['id']));
            $this->assertEquals(0, $db->tables['test_table']->columns['title']->required);

            $db->alter('test_table', function (Blueprint $table) {
                $table->int('price')->title("Price");
                $table->dropColumns('title');
            });

            $this->assertTrue($db->schema->hasTable('test_table'));
            $this->assertFalse($db->schema->hasTable('test_table__2'));

            $this->assertNull($db->connection->table('table_columns AS column')
                ->join('tables AS table', 'table.id', '=', 'column.table')
                ->where('table.name', '=', 'test_table')
                ->where('column.name', '=', 'title')
                ->value('column.required'));
            $this->assertEquals(0, $db->connection->table('table_columns AS column')
                ->join('tables AS table', 'table.id', '=', 'column.table')
                ->where('table.name', '=', 'test_table')
                ->where('column.name', '=', 'price')
                ->value('column.required'));

            $this->assertTrue(isset($db->tables['test_table']));
            $this->assertFalse(isset($db->tables['test_table']->columns['title']));
            $this->assertEquals(0, $db->tables['test_table']->columns['price']->required);

        }
        finally {
            $db->drop('test_table');
        }

        $this->assertFalse($db->schema->hasTable('test_table'));
        $this->assertNull($db->connection->table('tables')
            ->where('name', '=', 'test_table')
            ->value('id'));
        $this->assertNull($db->connection->table('table_columns AS column')
            ->join('tables AS table', 'table.id', '=', 'column.table')
            ->where('table.name', '=', 'test_table')
            ->where('column.name', '=', 'title')
            ->value('column.required'));

        $this->assertFalse(isset($db->tables['test_table']));
    }
}