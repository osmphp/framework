<?php

declare(strict_types=1);

namespace Osm\Framework\Data\Filters;

use Osm\Framework\Search\Query as SearchQuery;

class Equals extends ColumnFilter
{
    public function apply(SearchQuery $query): void {
        $query->whereEquals($this->column_name, $this->value);
    }

}