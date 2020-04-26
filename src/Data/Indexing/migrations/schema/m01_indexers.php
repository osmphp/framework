<?php

namespace Osm\Data\Indexing\Migrations\Schema;

use Illuminate\Database\Schema\Blueprint;
use Osm\Core\App;
use Osm\Data\Tables\Table;
use Osm\Framework\Migrations\Migration;

class m01_indexers extends Migration
{
    public function up() {
        $this->schema->create('indexers', function(Blueprint $table) {
            $table->increments('id');
            $table->string('group')->nullable();
            $table->string('target');
            $table->string('source');
            $table->string('events');
            $table->string('columns');
            $table->boolean('requires_partial_reindex')->default(false);
            $table->boolean('requires_full_reindex')->default(false);
        });
    }

    public function down() {
        $this->schema->drop('indexers');
    }
}