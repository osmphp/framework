<?php

declare(strict_types=1);

namespace Osm\Framework\AlgoliaSearch\Traits\Filters;

use Osm\Core\Exceptions\NotImplemented;

trait FilterTrait
{
    public function toAlgoliaQuery(): string {
        throw new NotImplemented();
    }
}