<?php

namespace Manadev\Tests\Data\TableQueries;

use Manadev\Core\App;
use Manadev\Data\Tables\Blueprint;
use Manadev\Framework\Testing\Tests\DbTestCase;

class RelationTest extends DbTestCase
{
    /**
     * @see \Manadev\Samples\Tables\Traits\RelationsTrait for relation definition.
     */
    public function test_relations() {
        global $m_app; /* @var App $m_app */

        $db = $m_app->db;

        $db->create('test_users', function (Blueprint $table) {
            $table->string('name')->title("Name")->required()->unique();
        });

        $db->create('test_tasks', function (Blueprint $table) {
            $table->string('title')->title("Title")->required();
            $table->int('user')->title("User")->unsigned()
                ->references('test_users.id')->on_delete('cascade');
        });

        try {
            $admin = $db['test_users']->insert(['name' => 'admin']);
            $task1 = $db['test_tasks']->insert(['title' => 'Task1', 'user' => $admin]);
            $task2 = $db['test_tasks']->insert(['title' => 'Task2']);

            // left join additional data - if mentioned in SELECT
            $this->assertEquals('admin', $db['test_tasks']
                ->where("id = ?", $task1)
                ->value("user.name"));
            $this->assertNull($db['test_tasks']
                ->where("id = ?", $task2)
                ->value("user.name"));

            // inner join additional data - if mentioned in filters
            $this->assertEquals(1, $db['test_tasks']
                ->where("user.name = ?", 'admin')
                ->get("id")
                ->count());

            // inner join takes precedence
            $this->assertEquals(1, $db['test_tasks']
                ->where("user.name = ?", 'admin')
                ->get("user.name")
                ->count());
        }
        finally {
            $db->drop('test_tasks');
            $db->drop('test_users');
        }

    }
}