<?php

declare(strict_types=1);

namespace Osm\Framework\Data;

use Illuminate\Database\Query\Builder as DbQuery;
use Osm\Core\Exceptions\NotImplemented;
use Osm\Core\Object_;
use Illuminate\Database\Schema\Blueprint as TableBlueprint;
use Osm\Framework\Search\Blueprint as IndexBlueprint;
use Osm\Core\Attributes\Serialized;

/**
 * @property string $sheet_name #[Serialized]
 * @property string $name #[Serialized]
 * @property string $type #[Serialized]
 * @property string $class_name #[Serialized]
 * @property int $partition_no #[Serialized]
 * @property int $partition_weight
 * @property bool $filterable #[Serialized]
 */
class Column extends Object_
{
    const INT_ = 'int';
    const STRING_ = 'string';
    const FLOAT_ = 'float';
    const BOOL_ = 'bool';
    const TEXT_ = 'text';
    const DATETIME_ = 'datetime';

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
        $query->select("this.{$this->name}");
    }

    /** @noinspection PhpUnused */
    protected function get_partition_weight(): int {
        if ($this->type == static::STRING_) {
            return 1;
        }

        return 0;
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