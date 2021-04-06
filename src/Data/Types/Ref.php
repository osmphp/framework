<?php

declare(strict_types=1);

namespace Osm\Framework\Data\Types;

use Osm\Core\Attributes\Name;
use Osm\Framework\Data\Enums\Types;
use Osm\Framework\Data\Type;

#[Name(Types::REF)]
class Ref extends Type
{
    public function save(array &$values, \stdClass $data): void {
        if (isset($data->{$this->column->name})) {
            $values["{$this->column->name}_id"] = $data->{$this->column->name};
        }
    }

}