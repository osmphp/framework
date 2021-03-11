<?php

declare(strict_types=1);

namespace Osm\Framework\Data;

use Osm\Core\Exceptions\NotImplemented;
use Osm\Core\Object_;

/**
 * @property string $sheet_name
 * @property Filters\Filter $filter
 */
class Query extends Object_
{
    public function insert(array $data): int {
        throw new NotImplemented();
    }

    public function bulkInsert(array $data): void {
        throw new NotImplemented();
    }

    public function whereEquals(string $columnName, mixed $value): static
    {
        $this->filter->filters[] = Filters\Equals::new([
            'field_name' => $columnName,
            'value' => $value,
        ]);

        return $this;
    }

    public function get(string ...$columnNames): Result {
        throw new NotImplemented();
    }

    public function count(): int {
        return $this->get('id')->count;
    }

    public function rows(string ...$columnNames): array {
        return $this->get(...$columnNames)->rows;
    }

    public function first(string ...$columnNames): ?\stdClass {
        return $this->rows(...$columnNames)[0] ?? null;
    }

    public function value($columnName): mixed {
        if (($row = $this->first($columnName)) === null) {
            return null;
        }

        foreach ($row as $property => $value) {
            return $value;
        }

        return null;
    }

    /** @noinspection PhpUnused */
    protected function get_filter(): Filters\LogicalFilter {
        return Filters\And_::new();
    }
}