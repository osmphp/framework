<?php

declare(strict_types=1);

namespace Osm\Framework\AlgoliaSearch\Traits;

use Osm\Core\Attributes\UseIn;
use Osm\Core\Exceptions\NotImplemented;
use Osm\Framework\Search\Filter;

#[UseIn(Filter::class)]
trait FilterTrait
{
    public function toAlgoliaQuery(): string {
        throw new NotImplemented();
    }
}