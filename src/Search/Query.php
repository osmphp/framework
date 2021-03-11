<?php

declare(strict_types=1);

namespace Osm\Framework\Search;

use Osm\Core\App;
use Osm\Core\BaseModule;
use Osm\Core\Object_;
use Osm\Framework\Search\Filters\And_;
use Osm\Framework\Search\Filters\LogicalFilter;

/**
 * @property Search $search
 * @property string $index_name
 * @property LogicalFilter $filter
 */
abstract class Query extends Object_
{
    abstract public function insert(array $data): void;
    abstract public function bulkInsert(array $data): void;

    public function whereEquals(string $fieldName, mixed $value): static
    {
        $this->filter->filters[] = Filters\Equals::new([
            'field_name' => $fieldName,
            'value' => $value,
        ]);

        return $this;
    }

    abstract public function get(): Result;

    public function count(): int {
        return $this->get()->count;
    }

    /**
     * @return string[]
     */
    public function ids(): array {
        return $this->get()->ids;
    }

    public function id(): ?string {
        return $this->get()->ids[0] ?? null;
    }

    /** @noinspection PhpUnused */
    protected function get_filter(): LogicalFilter {
        return And_::new();
    }
}