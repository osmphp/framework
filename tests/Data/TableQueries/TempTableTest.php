<?php

namespace Osm\Tests\Data\TableQueries;

use Osm\Core\App;
use Osm\Framework\Testing\Tests\DbTestCase;
use Osm\Data\Tables\Blueprint;

class TempTableTest extends DbTestCase
{
    public function test_creation_and_querying() {
        global $m_app; /* @var App $m_app */

        $db = $m_app->db;

        $table = $db->temp(function (Blueprint $table) {
            $table->int('id')->unsigned()->title("ID");
        });

        $db[$table]->insert(['id' => 1]);
        $this->assertEquals(1, $db[$table]->value("id"));

        // temp tables are dropped automatically by DB engine in the end of session
    }
}