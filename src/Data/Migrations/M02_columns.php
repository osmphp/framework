<?php

/** @noinspection PhpUnused */
declare(strict_types=1);

namespace Osm\Framework\Data\Migrations;

use Illuminate\Database\Schema\Blueprint;
use Osm\Core\App;
use Osm\Framework\Data\Enums\ForeignActions;
use Osm\Framework\Data\Enums\Indexes;
use Osm\Framework\Data\Enums\Types;
use Osm\Framework\Db\Db;
use Osm\Framework\Migrations\Migration;

/**
 * @property Db $db
 */
class M02_columns extends Migration
{
    protected function get_db(): Db {
        global $osm_app; /* @var App $osm_app */

        return $osm_app->db;
    }

    public function create(): void {
        $this->db->create('sheets__columns', function(Blueprint $table) {
            $table->increments('id');

            $table->unsignedInteger('sheet_id');
            $table->foreign('sheet_id')->references('id')
                ->on('sheets')->onDelete('cascade');

            $table->unsignedInteger('child_sheet_id')->nullable();
            $table->foreign('child_sheet_id')->references('id')
                ->on('sheets')->onDelete('cascade');

            $table->unsignedInteger('foreign_sheet_id')->nullable();
            $table->foreign('foreign_sheet_id')->references('id')
                ->on('sheets')->onDelete('set null');

            $table->string('name')->index();
            $table->unique(['sheet_id', 'name']);

            $table->string('type', 80);
            $table->unsignedSmallInteger('partition_no')->default(0);
            $table->boolean('unsigned')->nullable();
            $table->boolean('nullable')->nullable();
            $table->unsignedSmallInteger('length')->nullable();
            $table->string('index', 80)->nullable();
            $table->longText('default')->nullable();
            $table->string('foreign_action', 80)->nullable();
            $table->string('array', 80)->nullable();

            $table->boolean('filterable')->default(false);
            $table->boolean('sortable')->default(false);

            $table->string('formula', 80)->nullable();
        });
    }

    public function drop(): void {
        $this->db->drop('sheets__columns');
    }

    public function insert(): void {
        #region Get sheet IDs
        $sheets = $this->db->table('sheets')
            ->where('name', 'sheets')
            ->value('id');
        $columns = $this->db->table('sheets')
            ->where('name', 'sheets__columns')
            ->value('id');
        $options = $this->db->table('sheets')
            ->where('name', 'sheets__columns__options')
            ->value('id');
        #endregion

        #region sheets
        $this->db->table('sheets__columns')->insert([
            'sheet_id' => $sheets,
            'name' => 'id',
            'type' => Types::INT_,

            'unsigned' => true,
            'index' => Indexes::AUTO_INCREMENT,
        ]);
        $this->db->table('sheets__columns')->insert([
            'sheet_id' => $sheets,
            'name' => 'columns',
            'type' => Types::COMPUTED,

            'array' => true,
        ]);
        $this->db->table('sheets__columns')->insert([
            'sheet_id' => $sheets,
            'name' => 'name',
            'type' => Types::STRING_,

            'index' => Indexes::INDEX,
        ]);
        #endregion

        #region columns
        $this->db->table('sheets__columns')->insert([
            'sheet_id' => $columns,
            'name' => 'id',
            'type' => Types::INT_,

            'unsigned' => true,
            'index' => Indexes::AUTO_INCREMENT,
        ]);
        $this->db->table('sheets__columns')->insert([
            'sheet_id' => $columns,
            'name' => 'sheet',
            'type' => Types::INT_,

            'unsigned' => true,
            'nullable' => true,
            'index' => Indexes::INDEX,
            'foreign_sheet_id' => $sheets,
            'foreign_action' => ForeignActions::CASCADE,
        ]);
        $this->db->table('sheets__columns')->insert([
            'sheet_id' => $columns,
            'name' => 'options',
            'type' => Types::COMPUTED,

            'array' => true,
        ]);
        $this->db->table('sheets__columns')->insert([
            'sheet_id' => $columns,
            'name' => 'name',
            'type' => Types::STRING_,

            'index' => Indexes::INDEX,
        ]);
        #endregion

        #region options
        #endregion
    }
}