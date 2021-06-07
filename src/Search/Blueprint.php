<?php

declare(strict_types=1);

namespace Osm\Framework\Search;

use Osm\Core\Exceptions\NotImplemented;
use Osm\Core\Object_;
use Osm\Core\Traits\Observable;

/**
 * @property Search $search
 * @property string $index_name
 */
class Blueprint extends Object_
{
    use Observable;

    /**
     * @var Field[]
     */
    public array $fields = [];

    public function create(): void {
        throw new NotImplemented($this);
    }

    public function drop(): void {
        throw new NotImplemented($this);
    }

    public function exists(): bool {
        throw new NotImplemented($this);
    }

    public function int(string $fieldName): Field\Int_ {
        return $this->fields[$fieldName] = Field\Int_::new([
            'name' => $fieldName,
        ]);
    }

    public function string(string $fieldName): Field\String_ {
        return $this->fields[$fieldName] = Field\String_::new([
            'name' => $fieldName,
        ]);
    }

    public function float(string $fieldName): Field\Float_ {
        return $this->fields[$fieldName] = Field\Float_::new([
            'name' => $fieldName,
        ]);
    }

    public function bool(string $fieldName): Field\Bool_ {
        return $this->fields[$fieldName] = Field\Bool_::new([
            'name' => $fieldName,
        ]);
    }
}