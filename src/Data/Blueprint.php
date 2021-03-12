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
     * @var Columns\Column[]
     */
    public array $columns = [];

    public function id(): Columns\Id {
        $columnName = 'id';

        return $this->columns[$columnName] = Columns\Id::new([
            'sheet_name' => $this->sheet_name,
            'name' => $columnName,
        ]);
    }

    public function int(string $columnName): Columns\Int_ {
        return $this->columns[$columnName] = Columns\Int_::new([
            'sheet_name' => $this->sheet_name,
            'name' => $columnName,
        ]);
    }

    public function string(string $columnName): Columns\String_ {
        return $this->columns[$columnName] = Columns\String_::new([
            'sheet_name' => $this->sheet_name,
            'name' => $columnName,
        ]);
    }
}