<?php

declare(strict_types=1);

namespace Osm\Framework\ElasticSearch\Traits\Filters;

use Osm\Core\Exceptions\NotImplemented;

trait FilterTrait
{
    public function toElasticQuery(): array {
        throw new NotImplemented();
    }
}