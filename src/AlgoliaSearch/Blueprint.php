<?php

declare(strict_types=1);

namespace Osm\Framework\AlgoliaSearch;

use Algolia\AlgoliaSearch\SearchIndex;
use Osm\Core\Exceptions\NotImplemented;
use Osm\Framework\Search\Blueprint as BaseBlueprint;

/**
 * @property Search $search
 */
class Blueprint extends BaseBlueprint
{
    public function create(): void {
        $facets = '';
        foreach ($this->fields as $field) {
            if ($facets) {
                $facets .= ', ';
            }
            $facets .= $field->generateAlgoliaFacet();
        }

        $this->index()->setSettings([
            'attributesForFaceting' => [$facets],
        ])->wait();
    }

    public function alter(): void {
        throw new NotImplemented();
    }

    public function drop(): void {
        $this->index()->delete()->wait();
    }

    public function exists(): bool {
        return $this->index()->exists();
    }

    protected function index(): SearchIndex {
        return $this->search->client->initIndex(
            "{$this->search->index_prefix}{$this->index_name}");
    }
}