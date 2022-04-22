<?php

declare(strict_types=1);

namespace Osm\Framework\ElasticSearch;

use Osm\Core\Exceptions\NotImplemented;
use Osm\Framework\Search\Exceptions\InvalidQuery;
use Osm\Framework\Search\Query as BaseQuery;
use Osm\Framework\Search\Result;
use Osm\Framework\Search\Hints\Result\Facet as FacetResult;
use function Osm\__;
use function Osm\merge;

/**
 * @property Search $search
 */
class Query extends BaseQuery
{
    public function insert(array $data): void {
        if (isset($data['id'])) {
            $id = (string)$data['id'];
        }
        else {
            $id = null;
        }

        $request = $this->fireFunction('elastic:inserting', [
            'index' => "{$this->search->index_prefix}{$this->index_name}",
            'refresh' => $this->search->refresh,
            'id' => $id,
            'body' => $data,
        ]);

        $this->search->client->index($request);

        $this->fire('elastic:inserted', $data['id'] ?? null);
    }

    public function update(int|string $id, array $data): void {
        $request = $this->fireFunction('elastic:updating', [
            'index' => "{$this->search->index_prefix}{$this->index_name}",
            'refresh' => $this->search->refresh,
            'id' => (string)$id,
            'body' => [
                'doc' => $data,
            ],
        ]);

        $this->search->client->update($request);

        $this->fire('elastic:updated', $id);
    }

    public function delete(int|string $id): void {
        $request = $this->fireFunction('elastic:deleting', [
            'index' => "{$this->search->index_prefix}{$this->index_name}",
            'refresh' => $this->search->refresh,
            'id' => (string)$id,
        ]);

        $this->search->client->delete($request);

        $this->fire('elastic:deleted', $id);
    }

    public function deleteAll(): void {
        $request = $this->fireFunction('elastic:deleting_all', [
            'index' => "{$this->search->index_prefix}{$this->index_name}",
            'refresh' => $this->search->refresh,
            'body' => [
                'query' => [
                    'match_all' => (object)[],
                ],
            ],
        ]);

        $this->search->client->deleteByQuery($request);

        $this->fire('elastic:deleted_all');
    }

    public function bulkInsert(array $data): void {
        $body = [];
        foreach ($data as $item) {
            if (isset($item['id'])) {
                $body[] = ['index' => ['_id' => (string)$item['id']]];
            }
            else {
                $body[] = ['index' => []];
            }

            $body[] = $item;
        }

        $request = $this->fireFunction('elastic:bulk-inserting', [
            'index' => "{$this->search->index_prefix}{$this->index_name}",
            'refresh' => $this->search->refresh,
            'body' => $body,
        ]);

        $this->search->client->bulk($request);

        $this->fire('elastic:bulk-inserted', $request);
    }

    public function get(): Result {
        $query = $this->filter->toElasticQuery(root: true);
        $query = $this->searchElasticQuery($query);

        $request = [
            'index' => "{$this->search->index_prefix}{$this->index_name}",
            'body' => [
                'query' => $query,
            ],
            '_source' => false,
        ];

        if (!$this->count) {
            $request['track_total_hits'] = false;
        }

        $request = $this->sortElasticQuery($request);
        $request = $this->paginateElasticQuery($request);
        $request = $this->facetElasticQuery($request);

        $request = $this->fireFunction('elastic:getting', $request);
        $response = $this->search->client->search($request);

        $result = Result::new([
            'facets' => $this->parseElasticAggregations($response),
        ]);

        if ($this->count) {
            $result->count = $response['hits']['total']['value'];
        }

        if ($this->hits) {
            $result->ids = array_map(
                fn($item) => $this->convertId($item['_id']),
                $response['hits']['hits']);
        }

        return $this->fireFunction('elastic:got', $result, $response);
    }

    protected function searchElasticQuery(array $query): array {
        if (!$this->phrase) {
            return $query;
        }

        $count = 0;
        $phrase = preg_replace('#^"(.*)"$#m', '$1',
            $this->phrase, -1, $count);
        $match = ($count) ? 'match_phrase' : 'match';

        $should = [];
        foreach ($this->index->fields as $field) {
            if (!$field->searchable) {
                continue;
            }

            $should[] = [
                $match => [
                    $field->name => [
                        'query' => $phrase,
                        'fuzziness' => 1,
                        'boost' => 1,
                    ],
                ],
            ];
        }

        if (empty($should)) {
            return $query;
        }

        return merge($query, [
            'bool' => [
                'should' => $should,
                'minimum_should_match' => 1,
            ],
        ]);
    }

    protected function sortElasticQuery(array $request): array {
        if (!$this->order) {
            return $request;
        }

        $sort = [];

        foreach ($this->order->by as $by) {
            $field = $this->index->fields[$by->field_name];
            $sort[$field->elastic_raw_name] =
                $by->desc ? "desc": "asc";
        }

        $request['body']['sort'] = $sort;

        return $request;
    }

    protected function paginateElasticQuery(array $request): array {
        if (!$this->hits) {
            $request['body']['size'] = 0;
            return $request;
        }

        if ($this->offset !== null) {
            $request['body']['from'] = $this->offset;
        }

        if ($this->limit !== null) {
            $request['body']['size'] = $this->limit;
        }
        else {
            throw new InvalidQuery(__("Call 'limit()' before retrieving data from the search index"));
        }

        return $request;
    }

    protected function facetElasticQuery(array $request): array {
        if (empty($this->facets)) {
            return $request;
        }

        $aggregations = [];
        foreach ($this->facets as $facet) {
            if ($facet->count) {
                $aggregations["{$facet->field->elastic_raw_name}__counts"] = [
                    'terms' => [
                        'field' => $facet->field->elastic_raw_name,
                    ],
                ];
            }

            if ($facet->min || $facet->max) {
                $aggregations["{$facet->field->elastic_raw_name}__stats"] = [
                    'stats' => [
                        'field' => $facet->field->elastic_raw_name,
                    ],
                ];
            }
        }

        $request['body']['aggs'] = $aggregations;

        return $request;
    }

    protected function parseElasticAggregations(array $response): array {
        $facetResults = [];
        if (!isset($response['aggregations'])) {
            return $facetResults;
        }

        foreach ($this->facets as $facet) {
            /* @var FacetResult $facetResult */
            $facetResult = new \stdClass();
            if ($facet->count) {
                $facetResult->counts =
                    array_map(
                        fn($item) => (object)[
                            'value' => $item['key'],
                            'count' => $item['doc_count'],
                        ],
                        $response['aggregations']
                            ["{$facet->field->elastic_raw_name}__counts"]
                            ['buckets']);
            }

            if ($facet->min) {
                $facetResult->min = $response['aggregations']
                    ["{$facet->field->elastic_raw_name}__stats"]['min'];
            }

            if ($facet->max) {
                $facetResult->max = $response['aggregations']
                    ["{$facet->field->elastic_raw_name}__stats"]['max'];
            }

            $facetResults[$facet->field_name] = $facetResult;
        }

        return $facetResults;
    }
}