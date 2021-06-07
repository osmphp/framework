<?php

declare(strict_types=1);

namespace Osm\Framework\Search\Traits;

use Osm\Framework\Search\Exceptions\InvalidQuery;
use Osm\Framework\Search\Field;
use Osm\Framework\Search\Filter;
use Osm\Framework\Search\Index;
use Osm\Framework\Search\Query;
use Osm\Framework\Search\Where;
use function Osm\__;

/**
 * @property Filter\Logical $filter
 * @property Index $index
 */
trait Filterable
{
    public function where(string $fieldName, string $operator,
        mixed $value = null): static
    {
        $field = $this->field($fieldName);
        if (!$field->filterable) {
            throw new InvalidQuery(__("Can't filter by ':field' field",
                ['field' => $fieldName]));
        }

        $query = $this instanceof Query ? $this : $this->query;

        $this->filter->filters[] = Filter\Field::new([
            'query' => $query,
            'field_name' => $fieldName,
            'operator' => $operator,
            'value' => $value,
        ]);

        return $this;
    }

    public function and(callable $callback): static {
        return $this->logical('and', $callback);
    }

    public function or(callable $callback): static {
        return $this->logical('or', $callback);
    }

    protected function logical(string $operator, callable $callback): static {
        $query = $this instanceof Query ? $this : $this->query;

        $clause = Where::new([
            'query' => $query,
            'filter' => Filter\Logical::new(['operator' => $operator]),
        ]);

        $callback($clause);

        $this->filter->filters[] = $clause->filter;

        return $this;
    }

    protected function field(string $fieldName): Field {
        $query = $this instanceof Query ? $this : $this->query;

        if (!isset($query->index->fields[$fieldName])) {
            throw new InvalidQuery(__("Unknown field ':field'",
                ['field' => $fieldName]));
        }

        return $query->index->fields[$fieldName];
    }
}