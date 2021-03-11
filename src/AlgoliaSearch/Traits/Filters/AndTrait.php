<?php

declare(strict_types=1);

namespace Osm\Framework\AlgoliaSearch\Traits\Filters;

use Osm\Framework\Search\Filters\Filter;
use Osm\Framework\Search\Filters\LogicalFilter;

trait AndTrait
{
    use FilterTrait;

    /** @noinspection PhpUnused */
    public function toAlgoliaQuery(): string {
        /* @var LogicalFilter $this */
        return implode(" AND ",
            array_map(fn(Filter $filter) => $filter->toAlgoliaQuery(),
                $this->filters));
    }
}