<?php

declare(strict_types=1);

namespace Osm\Framework\Search\Migrations;

use Illuminate\Database\Schema\Blueprint;
use Osm\Core\App;
use Osm\Framework\Db\Db;
use Osm\Framework\Migrations\Migration;

/**
 * @property Db $db
 */
class M01_search_indexes extends Migration
{
    protected function get_db(): Db {
        global $osm_app; /* @var App $osm_app */

        return $osm_app->db;
    }

    public function create(): void {
        $this->db->create('search_indexes', function (Blueprint $table) {
            $table->string('search', 100);
            $table->string('index', 100);
            $table->unique(['search', 'index']);
            $table->json('data');
        });
    }

    public function drop(): void {
        $this->db->drop('search_indexes');
    }
}