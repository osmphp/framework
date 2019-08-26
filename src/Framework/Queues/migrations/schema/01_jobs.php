<?php

namespace Osm\Framework\Queues\Migrations\Schema;

use Illuminate\Database\Schema\Blueprint;
use Osm\Framework\Migrations\Migration;

class Jobs extends Migration
{
    public function up() {
        $this->schema->create('jobs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('queue')->index();
            $table->longText('payload');
            $table->unsignedTinyInteger('attempts');
            $table->unsignedInteger('reserved_at')->nullable();
            $table->unsignedInteger('available_at');
            $table->unsignedInteger('created_at');
        });
    }

    public function down() {
        $this->schema->drop('jobs');
    }
}