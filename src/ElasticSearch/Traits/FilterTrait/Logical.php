<?php

declare(strict_types=1);

namespace Osm\Framework\ElasticSearch\Traits\FilterTrait;

use Osm\Core\Exceptions\NotSupported;
use Osm\Framework\ElasticSearch\Traits\FilterTrait;
use Osm\Framework\Search\Filter;
use function Osm\__;
use function Osm\merge;

trait Logical
{
    use FilterTrait;

    /** @noinspection PhpUnused */
    public function toElasticQuery(bool $root = false): array {
        /* @var Filter\Logical|Logical $this */
        $filters = [];

        // if there is only one operand, don't apply any logical operator
        if (count($this->filters) == 1 && !$root) {
            foreach ($this->filters as $filter) {
                return $filter->toElasticQuery();
            }
        }

        foreach ($this->filters as $filter) {
            $filters[] = $filter->toElasticQuery();
        }

        return match ($this->operator) {
            'and' => [
                'bool' => [
                    'filter' => $filters,
                ]
            ],
            'or' => [
                'bool' => [
                    'should' => $filters,
                    'minimum_should_match' => 1,
                ]
            ],
            default => throw new NotSupported(__(
                "In search, logical operator ':operator' not supported",
                ['operator' => $this->operator])),
        };
    }
}