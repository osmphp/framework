<?php

declare(strict_types=1);

namespace Osm\Framework\AlgoliaSearch;

use Algolia\AlgoliaSearch\SearchIndex;
use Osm\Core\Exceptions\NotImplemented;
use Osm\Framework\Search\Blueprint as BaseBlueprint;
use Osm\Framework\Search\Order;

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

        $settings = [
            'attributesForFaceting' => $facets,
        ];

        $settings = $this->replicas($settings);

        $settings = $this->fireFunction('algolia:creating', $settings);

        $this->search->initIndex($this->index_name)
            ->setSettings($settings)
            ->wait();

        unset($settings['replicas']);

        foreach ($this->orders as $key => $order) {
            $ranking = array_map(
                fn(Order\By $by) =>
                    ($by->desc ? 'desc' : 'asc') . "({$by->field_name})",
                $order->by);

            $replicaSettings = $order->algolia_virtual
                ? [
                    'customRanking' => $ranking,
                    'relevancyStrictness' => 0
                ]
                : array_merge($settings, [
                    'customRanking' => $ranking,
                    'ranking' => [
                        'custom',
                        'typo',
                        'geo',
                        'words',
                        'filters',
                        'proximity',
                        'attribute',
                        'exact',
                    ],
                ]);

            $this->search->initIndex($this->index_name, $key)
                ->setSettings($replicaSettings)
                ->wait();
        }

        $this->fire('algolia:created');

        $this->search->register($this);
    }

    public function drop(): void {
        $this->search->initIndex($this->index_name)
            ->delete()
            ->wait();

        $this->search->unregister($this);
    }

    public function exists(): bool {
        return $this->search->initIndex($this->index_name)
            ->exists();
    }

    protected function replicas(array $settings): array {
        if (empty($this->orders)) {
            return $settings;
        }

        $replicas = [];

        foreach ($this->orders as $key => $order) {
            $replicas[] = $order->algolia_virtual
                ? "virtual({$this->search->indexName($this->index_name, $key)})"
                : "{$this->search->indexName($this->index_name, $key)}";
        }

        $settings['replicas'] = $replicas;

        return $settings;
    }
}