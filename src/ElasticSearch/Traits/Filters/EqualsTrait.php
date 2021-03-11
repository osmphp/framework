<?php

declare(strict_types=1);

namespace Osm\Framework\ElasticSearch\Traits\Filters;

use Osm\Framework\Search\Filters\FieldFilter;

trait EqualsTrait
{
    use FilterTrait;

    /** @noinspection PhpUnused */
    public function toElasticQuery(): array {
        /* @var FieldFilter $this */
        return [
            'term' => [
                $this->field_name => $this->value,
            ],
        ];
    }
}