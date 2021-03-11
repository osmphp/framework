<?php

declare(strict_types=1);

namespace Osm\Framework\AlgoliaSearch;

use Algolia\AlgoliaSearch\SearchIndex;
use Osm\Core\Exceptions\NotImplemented;
use Osm\Framework\Search\Query as BaseQuery;
use Osm\Framework\Search\Result;

/**
 * @property Search $search
 */
class Query extends BaseQuery
{
    public function insert(array $data): void {
        if (isset($data['id'])) {
            $data['objectID'] = (string)$data['id'];
            unset($data['id']);
        }

        $request = $this->index()->saveObject($data);

        if ($this->search->wait) {
            $request->wait();
        }
    }

    public function bulkInsert(array $data): void {
        foreach ($data as &$item) {
            if (isset($item['id'])) {
                $item['objectID'] = (string)$item['id'];
                unset($item['id']);
            }
        }

        $request = $this->index()->saveObjects($data);

        if ($this->search->wait) {
            $request->wait();
        }
    }

    public function get(): Result {
        $filters = $this->filter->toAlgoliaQuery();
        $response = $this->index()->search('', [
            'filters' => $filters,
            'attributesToRetrieve' => ['objectID'],
        ]);

        return Result::new([
            'count' => $response['nbHits'],
            'ids' => array_map(fn($item) => $item['objectID'], $response['hits']),
        ]);
    }

    protected function index(): SearchIndex {
        return $this->search->client->initIndex(
            "{$this->search->index_prefix}{$this->index_name}");
    }
}