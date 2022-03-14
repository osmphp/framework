<?php

declare(strict_types=1);

namespace Osm\Framework\AlgoliaSearch;

use Algolia\AlgoliaSearch\SearchIndex;
use Osm\Core\Exceptions\NotImplemented;
use Osm\Framework\Search\Exceptions\InvalidQuery;
use Osm\Framework\Search\Facet;
use Osm\Framework\Search\Query as BaseQuery;
use Osm\Framework\Search\Result;
use Osm\Framework\Search\Hints\Result\Facet as FacetResult;
use function Osm\__;

/**
 * @property Search $search
 */
class Query extends BaseQuery
{
    public function insert(array $data): void {
        if (isset($data['id'])) {
            $data['objectID'] = (string)$data['id'];
        }

        $data = $this->fireFunction('algolia:inserting', $data);

        $request = $this->search->initIndex($this->index_name)
            ->saveObject($data);

        if ($this->search->wait) {
            $request->wait();
        }

        $this->fire('algolia:inserted', $data['id'] ?? null);
    }

    public function bulkInsert(array $data): void {
        foreach ($data as &$item) {
            if (isset($item['id'])) {
                $item['objectID'] = (string)$item['id'];
            }
        }

        $data = $this->fireFunction('algolia:bulk-inserting', $data);

        $request = $this->search->initIndex($this->index_name)
            ->saveObjects($data);

        if ($this->search->wait) {
            $request->wait();
        }

        $this->fire('algolia:bulk-inserted', $data);
    }

    public function update(int|string $id, array $data): void {
        $data['objectID'] = (string)$id;

        $data = $this->fireFunction('algolia:updating', $data);

        $request = $this->search->initIndex($this->index_name)
            ->partialUpdateObject($data);

        if ($this->search->wait) {
            $request->wait();
        }

        $this->fire('algolia:updated', $id);
    }

    public function delete(int|string $id): void {
        $this->fire('algolia:deleting', $id);

        $request = $this->search->initIndex($this->index_name)
            ->deleteObject($id);

        if ($this->search->wait) {
            $request->wait();
        }

        $this->fire('algolia:deleted', $id);
    }

    public function get(): Result {
        $filters = $this->filter->toAlgoliaQuery();
        $key = $this->order
            ? $this->order->name . '__' . ($this->order->desc ? 'desc' : 'asc')
            : null;

        $request = [
            'filters' => $filters,
            'attributesToRetrieve' => ['objectID'],
        ];

        if (!$this->count) {
            // TODO
        }

        if ($this->offset) {
            $request['offset'] = $this->offset;
        }

        if ($this->limit) {
            $request['length'] = $this->limit;
        }
        elseif ($this->hits) {
            throw new InvalidQuery(__("Call 'limit()' before retrieving data from the search index"));
        }
        else {
            $request['length'] = 1;
        }

        if (!empty($this->facets)) {
            $request['facets'] = array_map(
                fn(Facet $facet) => $facet->field_name,
                $this->facets);
        }

        $response = $this->search->initIndex($this->index_name, $key)
            ->search((string)$this->phrase, $request);

        $result = Result::new([
            'facets' => $this->parseFacets($response),
        ]);

        if ($this->count) {
            $result->count = $response['nbHits'];
        }

        if ($this->hits) {
            $result->ids = array_map(
                fn($item) => $this->convertId($item['objectID']),
                $response['hits']
            );
        }

        return $result;
    }

    protected function parseFacets($response): array {
        $facetResults = [];

        if (empty($this->facets)) {
            return $facetResults;
        }

        foreach ($this->facets as $facet) {
            /* @var FacetResult $facetResult */
            $facetResult = new \stdClass();

            if ($facet->count) {
                $facetResult->counts = $this->parseFacetCounts($response, $facet);
            }

            if ($facet->min) {
                $facetResult->min = $response['facets_stats']
                    [$facet->field_name]['min'];
            }

            if ($facet->max) {
                $facetResult->max = $response['facets_stats']
                    [$facet->field_name]['max'];
            }

            $facetResults[$facet->field_name] = $facetResult;
        }

        return $facetResults;
    }

    protected function parseFacetCounts(array $response, Facet $facet): array {
        $facetCounts = [];

        foreach ($response['facets'][$facet->field_name] as $value => $count) {
            $facetCounts[] = (object)[
                'value' => $value,
                'count' => $count,
            ];
        }

        return $facetCounts;
    }
}