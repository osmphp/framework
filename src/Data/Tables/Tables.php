<?php

namespace Osm\Data\Tables;

use Osm\Core\App;
use Osm\Data\Tables\Columns\Column;
use Osm\Data\Tables\Commands\Command;
use Osm\Data\Tables\Hints\TableHint;
use Osm\Framework\Data\CollectionRegistry;
use Osm\Framework\Db\Db;
use Osm\Data\Tables\Columns\ColumnType;
use Osm\Data\Tables\Columns\ColumnTypes;

/**
 * @property Db $parent @required
 * @property ColumnTypes|ColumnType[] $column_types @required
 */
class Tables extends CollectionRegistry
{
    public $class_ = Table::class;
    public $not_found_message = "Table ':name' not found";

    protected function default($property) {
        global $m_app; /* @var App $m_app */

        switch ($property) {
            case 'column_types': return $m_app->cache->remember('table_column_types', function() {
                return ColumnTypes::new();
            });
        }
        return parent::default($property);
    }

    protected function get() {
        $result = [];

        $records = $this->parent->connection->table('tables')->get();
        foreach ($records as $data) {
            /* @var TableHint $data */
            $result[$data->name] = $table = Table::new((array)$data, null, $this);
            $table->db = $this->parent;
        }

        $this->modified();

        return $result;
    }

    /**
     * @param string $table
     * @param Column[] $columns
     */
    public function register($table, $columns) {
        $table_ = $this->parent->connection->table('tables')->insertGetId(['name' => $table]);
        $this->registerColumns($table_, $columns);
        $this->refresh();
    }

    /**
     * @param int $table_
     * @param Column[] $columns
     */
    public function registerColumns($table_, $columns) {
        foreach ($columns as $column) {
            $this->parent->connection->table('table_columns')->insert([
                'table' => $table_,
                'name' => $column->name,
                'title__default' => $column->title,
                'partition' => $column->partition,
                'pinned' => $column->pinned,
                'type' => $column->type,
                'required' => $column->required,
                'unsigned' => $column->unsigned,
                'length' => $column->length,
            ]);
        }
    }

    /**
     * @param int $table_
     * @param Command[] $commands
     */
    public function unregisterColumns($table_, $commands) {
        foreach ($commands as $command) {
            if ($command->type == Command::DROP_COLUMNS) {
                $this->parent->connection->table('table_columns')
                    ->where('table', '=', $table_)
                    ->whereIn('name', $command->columns)
                    ->delete();
            }
        }
    }

    public function unregister($table) {
        $this->parent->connection->table('tables')
            ->where('name', '=', $table)
            ->delete();

        $this->refresh();
    }
}