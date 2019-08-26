<?php

namespace Osm\Data\Indexing\Traits;

use Osm\Core\App;
use Osm\Data\Indexing\Indexing;

trait MigratorTrait
{
    protected function around_migrate(callable $proceed) {
        global $m_app; /* @var App $m_app */

        $proceed();

        /* @var Indexing $indexing */
        $indexing = $m_app[Indexing::class];
        $indexing->run_async = false;
    }

    protected function around_migrateBack(callable $proceed) {
        global $m_app; /* @var App $m_app */

        $proceed();

        /* @var Indexing $indexing */
        $indexing = $m_app[Indexing::class];
        $indexing->run_async = false;
    }

}