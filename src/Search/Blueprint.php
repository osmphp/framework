<?php

declare(strict_types=1);

namespace Osm\Framework\Search;

use Osm\Core\Object_;
use Osm\Framework\Search\Fields\Field;

/**
 * @property Search $search
 * @property string $index_name
 */
abstract class Blueprint extends Object_
{
    /**
     * @var Field[]
     */
    public array $fields = [];

    abstract public function create(): void;
    abstract public function alter(): void;
    abstract public function drop(): void;
    abstract public function exists(): bool;

    public function int(string $fieldName): Fields\Int_ {
        return $this->fields[$fieldName] = Fields\Int_::new([
            'name' => $fieldName,
        ]);
    }

    public function string(string $fieldName): Fields\String_ {
        return $this->fields[$fieldName] = Fields\String_::new([
            'name' => $fieldName,
        ]);
    }
}