<?php

declare(strict_types=1);

namespace Osm\Framework\AlgoliaSearch;

use Osm\Core\Exceptions\NotImplemented;
use Osm\Framework\Search\Blueprint as BaseBlueprint;

/**
 * @property Search $search
 */
class Blueprint extends BaseBlueprint
{
    public function create(): void {
        throw new NotImplemented();
    }

    public function alter(): void {
        throw new NotImplemented();
    }

    public function drop(): void {
        throw new NotImplemented();
    }

    public function exists(): bool {
        throw new NotImplemented();
    }
}