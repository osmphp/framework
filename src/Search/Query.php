<?php

declare(strict_types=1);

namespace Osm\Framework\Search;

use Osm\Core\App;
use Osm\Core\BaseModule;
use Osm\Core\Exceptions\NotImplemented;
use Osm\Core\Object_;
use Osm\Core\Traits\Observable;
use Osm\Framework\Search\Filters\And_;
use Osm\Framework\Search\Filters\LogicalFilter;

/**
 * @property Search $search
 * @property string $index_name
 * @property LogicalFilter $filter
 * @property Index $index
 */
abstract class Query extends Object_
{
    use Observable;

    abstract public function insert(array $data): void;
    abstract public function bulkInsert(array $data): void;

    public function where(string $fieldName, string $operator,
        mixed $value = null): static
    {
        $this->filter->filters[] = Filters\Field::new([
            'query' => $this,
            'field_name' => $fieldName,
            'operator' => $operator,
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

    protected function get_index(): Index {
        throw new NotImplemented($this);
    }
}