<?php

declare(strict_types=1);

namespace Osm\Framework\Data\Filters;

use Illuminate\Database\Query\Builder;
use Osm\Core\Exceptions\NotImplemented;
use Osm\Core\Object_;
use Osm\Framework\Search\Query as SearchQuery;

class Filter extends Object_
{
    public function apply(SearchQuery $query): void {
        throw new NotImplemented($this);
    }

    public function applyToDbQuery(Builder $query) {
        throw new NotImplemented($this);
    }
}