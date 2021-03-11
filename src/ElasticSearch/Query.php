<?php

declare(strict_types=1);

namespace Osm\Framework\ElasticSearch;

use Osm\Framework\Search\Query as BaseQuery;
use Osm\Framework\Search\Result;

/**
 * @property Search $search
 */
class Query extends BaseQuery
{
    public function insert(array $data): void {
        $this->search->client->index([
            'id' => $data['uid'] ?? null,
            'index' => "{$this->search->index_prefix}{$this->index_name}",
            'refresh' => $this->search->refresh,
            'body' => $data,
        ]);
    }

    public function get(): Result {
        $query = $this->filter->toElasticQuery();

        $response = $this->search->client->search([
            'index' => "{$this->search->index_prefix}{$this->index_name}",
            'body' => [
                'query' => $query,
            ],
            '_source' => false,
        ]);

        return Result::new([
            'count' => $response['hits']['total']['value'],
            'uids' => array_map(fn($item) => $item['_id'], $response['hits']['hits']),
        ]);
    }
}