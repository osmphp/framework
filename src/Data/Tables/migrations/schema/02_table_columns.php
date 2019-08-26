<?php

namespace Osm\Data\Tables\Migrations\Schema;

use Illuminate\Database\Schema\Blueprint;
use Osm\Framework\Migrations\Migration;

class TableColumns extends Migration
{
    public function up() {
        $this->schema->create('table_columns', function(Blueprint $table) {
            $table->increments('id');

            $table->unsignedInteger('table');
            $table->index('table');
            $table->foreign('table')->references('id')->on('tables')->onDelete('cascade');

            $table->string('name');
            $table->unique(['table', 'name']);

            // partitioning attributes

            $table->unsignedSmallInteger('partition');
            $table->boolean('pinned')->default(false);

            // title
            $table->string('title')->nullable();
            $table->string('title__default');
            $table->boolean('title__translate')->default(true);

            // general attributes

            $table->string('type');
            $table->boolean('required')->default(false);

            // column-type specific attributes

            $table->boolean('unsigned')->nullable();
            $table->unsignedSmallInteger('length')->nullable();
        });
    }

    public function down() {
        $this->schema->drop('table_columns');
    }
}