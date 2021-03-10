<?php

declare(strict_types=1);

namespace Osm\Framework\ElasticSearch;

use Osm\Core\Exceptions\NotImplemented;
use Osm\Framework\Search\Query as BaseQuery;
use Osm\Framework\Search\Result;

/**
 * @property Search $search
 */
class Query extends BaseQuery
{
    public function insert(array $data): void {
        $this->search->client->index([
            'index' => "{$this->search->index_prefix}{$this->index_name}",
            'body' => $data,
        ]);
    }

    public function get(): Result {
        throw new NotImplemented();
    }
}