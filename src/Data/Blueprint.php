<?php

declare(strict_types=1);

namespace Osm\Framework\Data;

use Osm\Core\Exceptions\NotImplemented;
use Osm\Core\Object_;

/**
 * @property string $sheet_name
 */
class Blueprint extends Object_
{
    /**
     * @var Column[]
     */
    public array $columns = [];

    public function id(): Column {
        return $this->int('id');
    }

    public function int(string $columnName): Column {
        return $this->column($columnName, Column::INT_);
    }

    public function string(string $columnName): Column {
        return $this->column($columnName, Column::STRING_);
    }

    protected function column(string $columnName, string $type): Column {
        return $this->columns[$columnName] = Column::new([
            'sheet_name' => $this->sheet_name,
            'name' => $columnName,
            'type' => $type,
        ]);
    }
}