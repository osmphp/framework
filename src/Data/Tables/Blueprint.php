<?php

namespace Osm\Data\Tables;

use Osm\Core\Object_;
use Osm\Framework\Db\Db;
use Osm\Data\Tables\Columns\Column;
use Osm\Data\Tables\Columns\ColumnType;
use Osm\Data\Tables\Columns\ColumnTypes;
use Osm\Data\Tables\Commands\Command;

/**
 * @property string $name @required @part
 * @property Db $db @required
 * @property ColumnTypes|ColumnType[] $column_types @required
 */
class Blueprint extends Object_
{
    /**
     * @required @part
     * @var Column[]
     */
    public $columns = [];

    /**
     * @required @part
     * @var Command[]
     */
    public $commands = [];

    protected function default($property) {
        switch ($property) {
            case 'column_types': return $this->db->tables->column_types;
        }
        return parent::default($property);
    }

    /**
     * @param $type
     * @param $column
     * @param array $data
     * @return Column
     */
    public function column($type, $column, $data = []) {
        $data['type']  = $type;
        $data['data_type'] = $this->column_types[$type]->data_type;

        $this->modified();
        return $this->columns[$column] = Column::new($data, $column, $this);
    }
    /**
     * @param string $column
     * @param int $length
     * @return Column
     */
    public function string($column, $length = 255) {
        $data = [];
        if ($length !== null) {
            $data['length'] = $length;
        }
        return $this->column(__FUNCTION__, $column, $data);

    }

    /**
     * @param string $column
     * @return Column
     */
    public function int($column) {
        return $this->column(__FUNCTION__, $column);
    }

    /**
     * @param string $column
     * @return Column
     */
    public function bool($column) {
        return $this->column(__FUNCTION__, $column);
    }

    /**
     * @param string $column
     * @return Column
     */
    public function text($column) {
        return $this->column(__FUNCTION__, $column);
    }

    /**
     * @param string $column
     * @return Column
     */
    public function datetime($column) {
        return $this->column(__FUNCTION__, $column);
    }

    public function decimal($column, $precision = 12, $scale = 4) {
        return $this->column(__FUNCTION__, $column, compact('precision', 'scale'));
    }

    public function unique(...$columns) {
        $this->commands[] = Command::new(['type' => Command::UNIQUE, 'columns' => $columns], null, $this);
    }

    public function dropColumns(...$columns) {
        $this->commands[] = Command::new(['type' => Command::DROP_COLUMNS, 'columns' => $columns], null, $this);
    }
}