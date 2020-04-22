<?php

namespace Osm\Framework\Queues\Migrations\Schema;

use Illuminate\Database\Schema\Blueprint;
use Osm\Framework\Migrations\Migration;

class m02_jobs extends Migration
{
    public function up() {
        $this->schema->create('jobs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('queue')->index();
            $table->string('class');

            $table->string('key')->nullable();
            $table->index('key');

            $table->string('status', 20)->index();
            $table->longText('error');
            $table->longText('log');
            $table->longText('stack_trace');
            $table->dateTime('registered_at')->nullable();
            $table->dateTime('processed_at')->nullable();
            $table->float('elapsed')->nullable();
        });
    }

    public function down() {
        $this->schema->drop('jobs');
    }
}