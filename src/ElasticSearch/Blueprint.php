<?php

declare(strict_types=1);

namespace Osm\Framework\ElasticSearch;

use Osm\Core\Exceptions\NotImplemented;
use Osm\Framework\Search\Blueprint as BaseBlueprint;
use Osm\Framework\Search\Fields\Field;

/**
 * @property Search $search
 */
class Blueprint extends BaseBlueprint
{
    public function create(): void {
        $request = $this->fireFunction('elastic:creating', [
            'index' => "{$this->search->index_prefix}{$this->index_name}",
            'body' => [
                'mappings' => [
                    'properties' => array_map(
                        fn (Field $field) => $field->generateElasticField(),
                        $this->fields
                    ),
                ],
            ],
        ]);

        $this->search->client->indices()->create($request);

        $this->fire('elastic:created');
    }

    public function drop(): void {
        $this->search->client->indices()->delete([
            'index' => "{$this->search->index_prefix}{$this->index_name}",
        ]);
    }

    public function exists(): bool {
        return $this->search->client->indices()->exists([
            'index' => "{$this->search->index_prefix}{$this->index_name}",
        ]);
    }
}