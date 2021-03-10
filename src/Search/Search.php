<?php

declare(strict_types=1);

namespace Osm\Framework\Search;

use Osm\Core\App;
use Osm\Core\Object_;
use Symfony\Component\Cache\Adapter\TagAwareAdapter;
use Symfony\Contracts\Cache\ItemInterface;

/**
 * @property array $config
 * @property ?string $index_prefix
 * @property TagAwareAdapter $cache
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
        $this->cache->delete("search_index|{$index}");
    }

    public function alter(string $index, callable $callback): void {
        $callback($blueprint = $this->createBlueprint([
            'search' => $this,
            'index_name' => $index,
        ]));
        $blueprint->alter();
        $this->cache->delete("search_index|{$index}");
    }

    public function drop(string $index): void {
        $blueprint = $this->createBlueprint([
            'search' => $this,
            'index_name' => $index,
        ]);
        $blueprint->drop();
        $this->cache->delete("search_index|{$index}");
    }

    public function hasIndex(string $index): bool {
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

    public function reflect(string $index) {
        return $this->cache->get("search_index|{$index}", function()
            use ($index)
        {
            return $this->createBlueprint([
                'search' => $this,
                'index_name' => $index,
            ])->reflect();
        });
    }

    /** @noinspection PhpUnused */
    protected function get_cache(): TagAwareAdapter {
        global $osm_app; /* @var App $osm_app */

        return $osm_app->cache;
    }
}