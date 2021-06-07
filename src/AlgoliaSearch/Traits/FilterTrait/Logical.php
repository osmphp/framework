<?php

declare(strict_types=1);

namespace Osm\Framework\AlgoliaSearch\Traits\FilterTrait;

use Osm\Framework\AlgoliaSearch\Traits\FilterTrait;
use Osm\Framework\Search\Filter;

trait Logical
{
    use FilterTrait;

    /** @noinspection PhpUnused */
    public function toAlgoliaQuery(): string {
        /* @var Filter\Logical $this */
        return implode(" AND ",
            array_map(fn(Filter $filter) => $filter->toAlgoliaQuery(),
                $this->filters));
    }
}