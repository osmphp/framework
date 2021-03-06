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
        if (isset($data['id'])) {
            $id = (string)$data['id'];
            unset($data['id']);
        }
        else {
            $id = null;
        }

        $this->search->client->index([
            'index' => "{$this->search->index_prefix}{$this->index_name}",
            'refresh' => $this->search->refresh,
            'id' => $id,
            'body' => $data,
        ]);
    }

    public function bulkInsert(array $data): void {
        $body = [];
        foreach ($data as $item) {
            if (isset($item['id'])) {
                $body[] = ['index' => ['_id' => (string)$item['id']]];
                unset($item['id']);
            }
            else {
                $body[] = ['index' => []];
            }

            $body[] = $item;
        }

        $this->search->client->bulk([
            'index' => "{$this->search->index_prefix}{$this->index_name}",
            'refresh' => $this->search->refresh,
            'body' => $body,
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
            'ids' => array_map(fn($item) => $item['_id'], $response['hits']['hits']),
        ]);
    }
}