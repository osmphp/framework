<?php

declare(strict_types=1);

namespace Osm\Framework\ElasticSearch\Traits;

use Osm\Core\Attributes\UseIn;
use Osm\Core\Exceptions\NotImplemented;
use Osm\Framework\Search\Filter;

#[UseIn(Filter::class)]
trait FilterTrait
{
    public function toElasticQuery(bool $root = false): array {
        throw new NotImplemented();
    }
}