<?php

declare(strict_types=1);

namespace Osm\Framework\AlgoliaSearch\Traits\Filter;


use Osm\Core\Attributes\UseIn;
use Osm\Framework\AlgoliaSearch\Traits\FilterTrait;
use Osm\Framework\Search\Filter;
use Osm\Framework\Search\Filter\Logical;

#[UseIn(Logical::class)]
trait LogicalTrait
{
    use FilterTrait;

    /** @noinspection PhpUnused */
    public function toAlgoliaQuery(): string {
        /* @var LogicalTrait|Logical $this */

        $operator = strtoupper($this->operator);

        return implode(" {$operator} ",
            array_map(fn(Filter $filter) => $filter->toAlgoliaQuery(),
                $this->filters));
    }
}