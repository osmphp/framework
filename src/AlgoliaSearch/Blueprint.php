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
        $this->addIdField();

        $facets = [];
        foreach ($this->fields as $field) {
            $facets[] = $field->generateAlgoliaFacet();
        }

        $settings = $this->fireFunction('algolia:creating', [
            'attributesForFaceting' => $facets,
        ]);

        $this->index()->setSettings($settings)->wait();

        $this->fire('algolia:created');

        $this->search->register($this);
    }

    public function drop(): void {
        $this->index()->delete()->wait();

        $this->search->unregister($this);
    }

    public function exists(): bool {
        return $this->index()->exists();
    }

    protected function index(): SearchIndex {
        return $this->search->client->initIndex(
            "{$this->search->index_prefix}{$this->index_name}");
    }
}