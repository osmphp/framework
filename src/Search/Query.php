<?php

declare(strict_types=1);

namespace Osm\Framework\Search;

use Osm\Core\Exceptions\NotImplemented;
use Osm\Core\Object_;
use Osm\Core\Traits\Observable;
use Osm\Framework\Search\Exceptions\InvalidQuery;
use Osm\Framework\Search\Filter\Logical;
use Osm\Framework\Search\Traits\Filterable;
use function Osm\__;

/**
 * @property Search $search
 * @property string $index_name
 *
 * @property string $phrase
 * @property int $offset
 * @property int $limit
 * @property Order $order
 *
 * @property Index $index
 */
class Query extends Object_
{
    use Observable, Filterable;

    /**
     * @var Facet[]
     */
    public array $facets = [];
    public bool $count = false;
    public bool $hits = true;

    public function insert(array $data): void {
        throw new NotImplemented($this);
    }

    public function update(int|string $id, array $data): void {
        throw new NotImplemented($this);
    }

    public function delete(int|string $id): void {
        throw new NotImplemented($this);
    }

    public function bulkInsert(array $data): void {
        throw new NotImplemented($this);
    }

    public function search(string $phrase): static {
        $this->phrase = $phrase;

        return $this;
    }

    public function offset(int $offset): static {
        $this->offset = $offset;

        return $this;
    }

    public function limit(int $limit): static {
        $this->limit = $limit;

        return $this;
    }

    public function facetBy(string $fieldName, bool $min = false, bool $max = false,
        bool $count = true): static
    {
        $field = $this->field($fieldName);
        if (!$field->faceted) {
            throw new InvalidQuery(__("Can't facet by ':field' field",
                ['field' => $fieldName]));
        }

        $this->facets[] = Facet::new([
            'query' => $this,
            'field_name' => $fieldName,
            'min' => $min,
            'max' => $max,
            'count' => $count,
        ]);

        return $this;
    }

    public function orderBy(string $orderName, bool $desc = false): static
    {
        $key = $orderName . '__' . ($desc ? 'desc' : 'asc');

        if (!isset($this->index->orders[$key])) {
            throw new InvalidQuery(__("Can't sort by ':order'",
                ['order' => $orderName]));
        }

        $this->order = $this->index->orders[$key];

        return $this;
    }

    public function get(): Result {
        throw new NotImplemented($this);
    }

    public function count(bool $count = true): static {
        $this->count = $count;

        return $this;
    }

    public function hits(bool $hits = true): static {
        $this->hits = $hits;

        return $this;
    }

    /**
     * @return string[]
     */
    public function ids(): array {
        return $this->get()->ids;
    }

    public function id(): string|int|null {
        return $this->limit(1)->get()->ids[0] ?? null;
    }

    /** @noinspection PhpUnused */
    protected function get_filter(): Logical {
        return Logical::new(['operator' => 'and']);
    }

    protected function get_index(): Index {
        return $this->search->indexes[$this->index_name];
    }

    protected function convertId(int|string $id): int|string {
        return is_numeric($id) ? (int)$id : $id;
    }
}