<?php

namespace Osm\Data\Indexing\Migrations\Schema;

use Illuminate\Database\Schema\Blueprint;
use Osm\Core\App;
use Osm\Data\Tables\Table;
use Osm\Framework\Migrations\Migration;

class RequiresFullReindexFix extends Migration
{
    public function up() {
        // initially this columns was 'string', should be 'bool'

        $this->schema->table('indexers', function(Blueprint $table) {
            $table->dropColumn('requires_full_reindex');
        });

        $this->schema->table('indexers', function(Blueprint $table) {
            $table->boolean('requires_full_reindex')->default(false);
        });
    }
}