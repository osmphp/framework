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

        $operator = strtoupper($this->operator);

        return implode(" {$operator} ",
            array_map(fn(Filter $filter) => $filter->toAlgoliaQuery(),
                $this->filters));
    }
}