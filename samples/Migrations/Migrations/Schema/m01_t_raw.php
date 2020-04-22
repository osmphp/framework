<?php

namespace Osm\Samples\Migrations\Migrations\Schema;

use Illuminate\Database\Schema\Blueprint;
use Osm\Framework\Migrations\Migration;

class m01_t_raw extends Migration
{
    public function up() {
        $this->schema->create('t_raw', function(Blueprint $table) {
            $table->increments('id');
        });
    }

    public function down() {
        $this->schema->drop('t_raw');
    }
}