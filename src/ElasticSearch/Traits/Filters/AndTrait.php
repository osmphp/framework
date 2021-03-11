<?php

declare(strict_types=1);

namespace Osm\Framework\ElasticSearch\Traits\Filters;

use Osm\Framework\Search\Filters\LogicalFilter;
use function Osm\merge;

trait AndTrait
{
    use FilterTrait;

    /** @noinspection PhpUnused */
    public function toElasticQuery(): array {
        /* @var LogicalFilter $this */
        $filters = [];
        foreach ($this->filters as $filter) {
            $filters = merge($filters, $filter->toElasticQuery());
        }

        return [
            'bool' => [
                'filter' => $filters,
            ],
        ];
    }
}