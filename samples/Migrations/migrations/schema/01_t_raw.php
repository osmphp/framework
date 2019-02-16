<?php

namespace Manadev\Samples\Migrations\Migrations\Schema;

use Illuminate\Database\Schema\Blueprint;
use Manadev\Framework\Migrations\Migration;

class TRaw extends Migration
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