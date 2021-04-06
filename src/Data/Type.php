<?php

declare(strict_types=1);

namespace Osm\Framework\Data;

use Osm\Core\Object_;

/**
 * @property Column $column
 */
class Type extends Object_
{
    public function save(array &$values, \stdClass $data): void {
        if (isset($data->{$this->column->name})) {
            $values[$this->column->name] = $data->{$this->column->name};
        }
    }

    public function insertIntoChildSheet(\stdClass $data): void {
        // by default, do nothing
    }
}