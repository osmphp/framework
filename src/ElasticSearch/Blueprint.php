<?php

declare(strict_types=1);

namespace Osm\Framework\ElasticSearch;

use Osm\Framework\Search\Blueprint as BaseBlueprint;
use function Osm\merge;

/**
 * @property Search $search
 */
class Blueprint extends BaseBlueprint
{
    public function create(): void {
        $this->addIdField();

        $properties = [];

        foreach ($this->fields as $field) {
            $properties = merge($properties, $field->generateElasticField());
        }

        $request = $this->fireFunction('elastic:creating', [
            'index' => "{$this->search->index_prefix}{$this->index_name}",
            'body' => [
                'mappings' => [
                    'properties' => $properties,
                ],
            ],
        ]);

        $this->search->client->indices()->create($request);

        $this->fire('elastic:created');

        $this->search->register($this);
    }

    public function drop(): void {
        $this->search->client->indices()->delete([
            'index' => "{$this->search->index_prefix}{$this->index_name}",
        ]);

        $this->search->unregister($this);
    }

    public function exists(): bool {
        return $this->search->client->indices()->exists([
            'index' => "{$this->search->index_prefix}{$this->index_name}",
        ]);
    }
}