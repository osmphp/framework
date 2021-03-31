<?php

/** @noinspection PhpUnused */
declare(strict_types=1);

namespace Osm\Framework\Data\Migrations;

use Illuminate\Database\Schema\Blueprint;
use Osm\Core\App;
use Osm\Framework\Db\Db;
use Osm\Framework\Migrations\Migration;

/**
 * @property Db $db
 */
class M01_columns extends Migration
{
    protected function get_db(): Db {
        global $osm_app; /* @var App $osm_app */

        return $osm_app->db;
    }

    public function create(): void {
        $this->db->create('columns', function(Blueprint $table) {
            $table->string('sheet_name')->index();
            $table->string('name');
            $table->unique(['sheet_name', 'name']);
            $table->string('class_name');
            $table->smallInteger('partition_no')->unsigned();
            $table->boolean('filterable');
        });
    }

    public function drop(): void {
        $this->db->drop('columns');
    }
}