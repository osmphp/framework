<?php

declare(strict_types=1);

namespace Osm\Framework\Data\Filters;

use Osm\Framework\Search\Query as SearchQuery;

class And_ extends LogicalFilter
{
    public function apply(SearchQuery $query): void {
        foreach ($this->filters as $filter) {
            $filter->apply($query);
        }
    }
}