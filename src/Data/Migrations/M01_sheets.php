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
class M01_sheets extends Migration
{
    protected function get_db(): Db {
        global $osm_app; /* @var App $osm_app */

        return $osm_app->db;
    }

    public function create(): void {
        $this->db->create('sheets', function(Blueprint $table) {
            $table->increments('id');
            $table->integer('parent_id')->unsigned();
            $table->string('name')->index();

            $table->foreign('parent_id')
                ->references('id')
                ->on('sheets')
                ->onDelete('cascade');
        });
    }

    public function drop(): void {
        $this->db->drop('sheets');
    }
}