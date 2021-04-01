<?php

/** @noinspection PhpUnused */
declare(strict_types=1);

namespace Osm\Framework\Samples\Migrations\Migrations;

use Illuminate\Database\Schema\Blueprint;
use Osm\Core\App;
use Osm\Framework\Db\Db;
use Osm\Framework\Migrations\Migration;

/**
 * @property Db $db
 */
class M01_t_migrated extends Migration
{
    protected function get_db(): Db {
        global $osm_app; /* @var App $osm_app */

        return $osm_app->db;
    }

    public function create(): void {
        $this->db->create('t_migrated', function(Blueprint $table) {
            $table->increments('id');
            $table->string('sku');
            $table->float('price');
        });
    }

    public function drop(): void {
        $this->db->drop('t_migrated');
    }

    public function insert(): void {
        $this->db->table('t_migrated')->insert([
            'sku' => 'P1',
            'price' => 10.0,
        ]);
    }
}