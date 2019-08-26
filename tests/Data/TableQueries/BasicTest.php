<?php

namespace Osm\Tests\Data\TableQueries;

use Osm\Core\App;
use Osm\Data\Tables\Blueprint;
use Osm\Framework\Testing\Tests\DbTestCase;

class BasicTest extends DbTestCase
{
    public function test_operations() {
        global $osm_app; /* @var App $osm_app */

        $db = $osm_app->db;

        $db->create('test_table', function (Blueprint $table) {
            $table->string('sku')->title("SKU")->required()->unique();
            $table->string('title')->title("Title")->partition(2);
        });

        try {
            $a1 = $db['test_table']->insert(['sku' => 'a1']);
            $db['test_table']->insert(['sku' => 'a2']);
            $db['test_table']->insert(['sku' => 'b1', 'title' => 'Beta 1']);
            $db['test_table']->insert(['sku' => 'b2', 'title' => 'Beta 2']);

            $this->assertNull($db['test_table']->where("id = ?", $a1)->value("title"));

            $db['test_table']->where("id = ?", $a1)->update(['title' => 'Alpha 1']);
            $this->assertEquals('Alpha 1', $db['test_table']->where("id = ?", $a1)->value("title"));

            $db['test_table']->where("title = 'Alpha 1'")->delete();
            $this->assertEquals(3, $db['test_table']->values("id")->count());
        }
        finally {
            $db->drop('test_table');
        }
    }
}