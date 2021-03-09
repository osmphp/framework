<?php

declare(strict_types=1);

namespace Osm\Framework\ElasticSearch;

use Osm\Framework\Search\Blueprint as BaseBlueprint;

/**
 * @property Search $search
 */
class Blueprint extends BaseBlueprint
{

    public function create(): void {

    }

    public function alter(): void {

    }

    public function drop(): void {

    }

    public function exists(): bool {
        return $this->search->client->indices()->exists([
            'index' => "{$this->search->index_prefix}{$this->index_name}",
        ]);
    }
}