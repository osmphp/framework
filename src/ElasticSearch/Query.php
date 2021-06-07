<?php

declare(strict_types=1);

namespace Osm\Framework\ElasticSearch;

use Osm\Core\Exceptions\NotImplemented;
use Osm\Framework\Search\Query as BaseQuery;
use Osm\Framework\Search\Result;
use Osm\Framework\Search\Hints\Result\Facet as FacetResult;
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

        $this->search->client->index([
            'index' => "{$this->search->index_prefix}{$this->index_name}",
            'refresh' => $this->search->refresh,
            'id' => $id,
            'body' => $data,
        ]);
    }

    public function update(int|string $id, array $data): void {
        $this->search->client->update([
            'index' => "{$this->search->index_prefix}{$this->index_name}",
            'refresh' => $this->search->refresh,
            'id' => (string)$id,
            'body' => [
                'doc' => $data,
            ],
        ]);
    }

    public function delete(int|string $id): void {
        $this->search->client->delete([
            'index' => "{$this->search->index_prefix}{$this->index_name}",
            'refresh' => $this->search->refresh,
            'id' => (string)$id,
        ]);
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

        $this->search->client->bulk([
            'index' => "{$this->search->index_prefix}{$this->index_name}",
            'refresh' => $this->search->refresh,
            'body' => $body,
        ]);
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

        $request = $this->sortElasticQuery($request);
        $request = $this->paginateElasticQuery($request);
        $request = $this->facetElasticQuery($request);

        $response = $this->search->client->search($request);

        return Result::new([
            'count' => $response['hits']['total']['value'],
            'ids' => array_map(
                fn($item) => is_numeric($item['_id'])
                    ? (int)$item['_id']
                    : $item['_id'],
                $response['hits']['hits']),
            'facets' => $this->parseElasticAggregations($response),
        ]);
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
                        'boost' => 1,
                        //'auto_generate_synonyms_phrase_query' => false,
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
        if (empty($this->orders)) {
            return $request;
        }

        $sort = [];
        foreach ($this->orders as $order) {
            $sort[$order->field->elastic_raw_name] =
                $order->desc ? "desc": "asc";
        }

        $request['body']['sort'] = $sort;

        return $request;
    }

    protected function paginateElasticQuery(array $request): array {
        if ($this->offset !== null) {
            $request['body']['from'] = $this->offset;
        }

        if ($this->limit !== null) {
            $request['body']['size'] = $this->limit;
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