<?php

namespace Osm\Data\Indexing\Traits;

use Osm\Core\App;
use Osm\Data\Indexing\Indexing;

trait MigratorTrait
{
    protected function around_migrate(callable $proceed) {
        global $osm_app; /* @var App $osm_app */

        /* @var Indexing $indexing */
        $indexing = $osm_app[Indexing::class];
        $indexing->stopQueueing();

        try {
            return $proceed();
        }
        finally {
            $indexing->resumeQueueing();
        }
    }

    protected function around_migrateBack(callable $proceed) {
        global $osm_app; /* @var App $osm_app */

        /* @var Indexing $indexing */
        $indexing = $osm_app[Indexing::class];
        $indexing->stopQueueing();

        try {
            return $proceed();
        }
        finally {
            $indexing->resumeQueueing();
        }
    }

}