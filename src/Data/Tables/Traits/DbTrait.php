<?php

namespace Manadev\Data\Tables\Traits;

use Manadev\Data\Tables\Blueprint;
use Manadev\Data\Tables\Columns\Columns;
use Manadev\Data\Tables\Table;
use Manadev\Framework\Db\Db;

trait DbTrait
{
    public function create($table, callable $callback) {
        $blueprint = Blueprint::new(['db' => $this], $table);
        $callback($blueprint);
        $table_ = Table::new(['name' => $table, 'db' => $this, 'blueprint' => $blueprint]);
        $table_->create();
    }

    public function temp(callable $callback) {
        $db = $this; /* @var Db $db */

        $table = uniqid('temp_');
        $blueprint = Blueprint::new(['db' => $this], $table);
        $callback($blueprint);
        $table_ = Table::new(['name' => $table, 'db' => $this, 'temp' => true, 'blueprint' => $blueprint]);
        $table_->columns = Columns::new(['items' => $blueprint->columns], null, $table_);
        $table_->create();
        $db->temp_tables[$table] = $table_;
        return $table;
    }

    public function alter($table, callable $callback) {
        $db = $this; /* @var Db $db */
        $blueprint = Blueprint::new(['db' => $this], $table);
        $callback($blueprint);
        $table_ = $db->tables[$table];
        $table_->blueprint = $blueprint;
        $table_->alter();
    }

    public function drop($table) {
        $db = $this; /* @var Db $db */
        $table_ = $db->tables[$table];
        $table_->drop();
    }
}