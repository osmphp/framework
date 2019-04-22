<?php

namespace Manadev\Data\Indexing\Migrations\Schema;

use Illuminate\Database\Schema\Blueprint;
use Manadev\Core\App;
use Manadev\Data\Tables\Table;
use Manadev\Framework\Migrations\Migration;

/**
 * @property Table $table @required
 */
class Indexers extends Migration
{
    public function default($property) {
        global $m_app; /* @var App $m_app */

        switch ($property) {
            case 'table': return $m_app->db->tables['indexers'];
        }
        return parent::default($property);
    }

    public function up() {
        $this->schema->create('indexers', function(Blueprint $table) {
            $table->increments('id');
            $table->string('target');
            $table->string('source');
            $table->string('events');
            $table->string('columns');
            $table->boolean('requires_partial_reindex')->default(false);
            $table->string('requires_full_reindex')->default(false);
        });
    }

    public function down() {
        $this->schema->drop('indexers');
    }
}