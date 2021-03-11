<?php

declare(strict_types=1);

namespace Osm\Framework\Search;

use Osm\Core\Object_;

/**
 * @property array $config
 * @property ?string $index_prefix
 */
abstract class Search extends Object_
{
    public static ?string $name;

    public function create(string $index, callable $callback): void {
        $callback($blueprint = $this->createBlueprint([
            'search' => $this,
            'index_name' => $index,
        ]));
        $blueprint->create();
    }

    public function alter(string $index, callable $callback): void {
        $callback($blueprint = $this->createBlueprint([
            'search' => $this,
            'index_name' => $index,
        ]));
        $blueprint->alter();
    }

    public function drop(string $index): void {
        $blueprint = $this->createBlueprint([
            'search' => $this,
            'index_name' => $index,
        ]);
        $blueprint->drop();
    }

    public function exists(string $index): bool {
        return $this->createBlueprint([
            'search' => $this,
            'index_name' => $index,
        ])->exists();
    }

    public function index(string $index): Query {
        return $this->createQuery([
            'search' => $this,
            'index_name' => $index,
        ]);
    }

    abstract protected function createBlueprint($data): Blueprint;
    abstract protected function createQuery($data): Query;
}