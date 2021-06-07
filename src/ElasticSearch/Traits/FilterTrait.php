<?php

declare(strict_types=1);

namespace Osm\Framework\ElasticSearch\Traits;

use Osm\Core\Exceptions\NotImplemented;

trait FilterTrait
{
    public function toElasticQuery(bool $root = false): array {
        throw new NotImplemented();
    }
}