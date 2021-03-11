<?php

declare(strict_types=1);

namespace Osm\Framework\AlgoliaSearch;

use Osm\Core\Exceptions\NotImplemented;
use Osm\Framework\Search\Query as BaseQuery;
use Osm\Framework\Search\Result;

/**
 * @property Search $search
 */
class Query extends BaseQuery
{
    public function insert(array $data): void {
        throw new NotImplemented();
    }

    public function bulkInsert(array $data): void {
        throw new NotImplemented();
    }

    public function get(): Result {
        throw new NotImplemented();
    }
}