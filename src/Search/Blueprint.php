<?php

declare(strict_types=1);

namespace Osm\Framework\Search;

use Osm\Core\Exceptions\NotImplemented;
use Osm\Core\Object_;
use Osm\Core\Traits\Observable;
use function Osm\merge;

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

    /**
     * @var Order[]
     */
    public array $orders = [];

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
            'blueprint' => $this,
            'name' => $fieldName,
        ]);
    }

    public function string(string $fieldName): Field\String_ {
        return $this->fields[$fieldName] = Field\String_::new([
            'blueprint' => $this,
            'name' => $fieldName,
        ]);
    }

    public function float(string $fieldName): Field\Float_ {
        return $this->fields[$fieldName] = Field\Float_::new([
            'blueprint' => $this,
            'name' => $fieldName,
        ]);
    }

    public function bool(string $fieldName): Field\Bool_ {
        return $this->fields[$fieldName] = Field\Bool_::new([
            'blueprint' => $this,
            'name' => $fieldName,
        ]);
    }

    protected function addIdField(): void {
        $this->fields = merge([
            'id' => Field\Int_::new([
                    'blueprint' => $this,
                    'name' => 'id',
                ])
                ->filterable()
                ->sortable(),
        ], $this->fields);
    }

    public function order(string $name, bool $desc = false): Order {
        $key = $name . '|' . ($desc ? 'desc' : 'asc');

        return $this->orders[$key] = Order::new([
            'blueprint' => $this,
            'name' => $name,
            'desc' => $desc,
            'by' => [],
        ]);
    }
}