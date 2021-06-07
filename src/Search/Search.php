<?php

declare(strict_types=1);

namespace Osm\Framework\Search;

use Osm\Core\App;
use Osm\Core\Exceptions\NotImplemented;
use Osm\Core\Object_;
use Osm\Framework\Cache\Cache;
use Osm\Framework\Db\Db;
use function Osm\create;
use function Osm\dehydrate;
use Osm\Framework\Cache\Attributes\Cached;
use function Osm\hydrate;

/**
 * @property string $name Name of the search engine connection, the default one
 *      is 'search'
 * @property array $config Connection configuration, specific to the search
 *      engine
 * @property ?string $index_prefix Prefix added to actual search index names
 *      in order to use the same search engine by several applications
 * @property Index[] $indexes #[Cached('search_indexes|{name}')]
 *      Schema information about all the indexes
 * @property Db $db
 * @property Cache $cache
 */
abstract class Search extends Object_
{
    public function create(string $index, callable $callback): void {
        $callback($blueprint = $this->createBlueprint([
            'search' => $this,
            'index_name' => $index,
        ]));
        $blueprint->create();
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

    public function register(Blueprint $index): void {
        $this->db->table('search_indexes')->insert([
            'search' => $this->name,
            'index' => $index->index_name,
            'data' => json_encode(dehydrate(Index::new([
                'name' => $index->index_name,
                'fields' => $index->fields,
            ]))),
        ]);

        $this->refresh();
    }

    public function unregister(Blueprint $index): void {
        $this->db->table('search_indexes')
            ->where('search', $this->name)
            ->where('index', $index->index_name)
            ->delete();

        $this->refresh();
    }

    protected function refresh(): void {
        $this->cache->deleteItem("search_indexes|{$this->name}");
    }

    protected function get_indexes(): array {
        $items = $this->db->table('search_indexes')
            ->where('search', $this->name)
            ->get('*');

        $indexes = [];

        foreach ($items as $item) {
            $indexes[$item->index] = $this->hydrateIndex(json_decode($item->data));
        }

        return $indexes;
    }

    protected function get_db(): Db {
        global $osm_app; /* @var App $osm_app */

        return $osm_app->db;
    }

    protected function get_cache(): Cache {
        global $osm_app; /* @var App $osm_app */

        return $osm_app->cache;
    }

    protected function hydrateIndex(\stdClass $data): Index {
        $data = (array)$data;

        if (isset($data['fields'])) {
            $data['fields'] = (array)$data['fields'];
            foreach ($data['fields'] as &$field) {
                $field = $this->hydrateField($field);
            }
        }

        return Index::new($data);
    }

    protected function hydrateField(\stdClass $field): Field|Object_ {
        return create(Field::class, $field->type ?? null,
            (array)$field);
    }
}