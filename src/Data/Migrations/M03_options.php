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
class M03_options extends Migration
{
    protected function get_db(): Db {
        global $osm_app; /* @var App $osm_app */

        return $osm_app->db;
    }

    public function create(): void {
        $this->db->create('sheets__columns__options', function(Blueprint $table) {
            $table->increments('id');

            $table->unsignedInteger('column_id');
            $table->foreign('column_id')->references('id')
                ->on('sheets__columns')->onDelete('cascade');

            $table->string('title');
            $table->integer('sort_order')->default(0);
        });
    }

    public function drop(): void {
        $this->db->drop('sheets__columns__options');
    }
}