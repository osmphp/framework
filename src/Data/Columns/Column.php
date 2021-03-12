<?php

declare(strict_types=1);

namespace Osm\Framework\Data\Columns;

use Illuminate\Database\Query\Builder as DbQuery;
use Osm\Core\Exceptions\NotImplemented;
use Osm\Core\Object_;
use Illuminate\Database\Schema\Blueprint as TableBlueprint;
use Osm\Framework\Search\Blueprint as IndexBlueprint;
use Osm\Core\Attributes\Serialized;

/**
 * @property string $sheet_name #[Serialized]
 * @property string $name #[Serialized]
 * @property string $class_name #[Serialized]
 * @property int $partition_no #[Serialized]
 * @property int $partition_weight
 * @property bool $filterable #[Serialized]
 */
class Column extends Object_
{
    public function createInTable(TableBlueprint $table): void {
        throw new NotImplemented();
    }

    public function createInIndex(IndexBlueprint $index): void {
        throw new NotImplemented();
    }

    public function toDbRecord(): array {
        $data = [];

        foreach ($this->__class->properties as $property) {
            if (isset($property->attributes[Serialized::class])) {
                $data[$property->name] = $this->{$property->name};
            }
        }

        return $data;
    }

    public function save(array &$values, \stdClass $data): void {
        throw new NotImplemented();
    }

    public function index(array &$values, \stdClass $data): void {
        throw new NotImplemented();
    }

    public function select(DbQuery $query): void {
        throw new NotImplemented();
    }

    /** @noinspection PhpUnused */
    protected function get_partition_weight(): int {
        throw new NotImplemented();
    }

    /** @noinspection PhpUnused */
    protected function get_class_name(): string {
        return $this->__class->name;
    }

    public function partition_no(int $value): static {
        $this->partition_no = $value;

        return $this;
    }

    public function filterable(bool $value = true): static {
        $this->filterable = $value;

        return $this;
    }

    /** @noinspection PhpUnused */
    protected function get_filterable(): bool {
        return false;
    }
}