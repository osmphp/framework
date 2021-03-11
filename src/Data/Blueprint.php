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

    public function int(string $columnName): Columns\Int_ {
        return $this->columns[$columnName] = Columns\Int_::new([
            'blueprint' => $this,
            'name' => $columnName,
        ]);
    }

    public function string(string $columnName): Columns\String_ {
        return $this->columns[$columnName] = Columns\String_::new([
            'blueprint' => $this,
            'name' => $columnName,
        ]);
    }
}