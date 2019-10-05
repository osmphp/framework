<?php

namespace Osm\Data\Indexing\Traits;

use Osm\Core\App;
use Osm\Data\Indexing\Indexing;
use Osm\Data\TableQueries\TableQuery;

trait TableQueryTrait
{
    /** @noinspection PhpUnused */
    protected function around_insert(callable $proceed, ...$args) {
        return $this->registerModifiedIndexingSource($proceed(...$args));
    }

    /** @noinspection PhpUnused */
    protected function around_update(callable $proceed, ...$args) {
        return $this->registerModifiedIndexingSource($proceed(...$args));
    }

    /** @noinspection PhpUnused */
    protected function around_delete(callable $proceed, ...$args) {
        return $this->registerModifiedIndexingSource($proceed(...$args));
    }

    /** @noinspection PhpUnused */
    protected function around_into(callable $proceed, $table, ...$args) {
        return $this->registerModifiedIndexingSource($proceed($table, ...$args),
            $table);
    }

    protected function registerModifiedIndexingSource($result, $source = null) {
        global $osm_app; /* @var App $osm_app */

        if (!$source) {
            /* @var TableQuery $query */
            $query = $this;

            $source = $query->tables['this']->table;
        }

        /* @var Indexing $indexing */
        $indexing = $osm_app[Indexing::class];

        $indexing->registerModifiedSource($source);

        return $result;
    }
}