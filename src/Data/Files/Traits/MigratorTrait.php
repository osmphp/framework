<?php

namespace Osm\Data\Files\Traits;

use Osm\Core\App;
use Osm\Data\Files\Files;
use Osm\Framework\Migrations\Migrator;

trait MigratorTrait
{
    protected function around_migrate(callable $proceed) {
        global $osm_app; /* @var App $osm_app */
        $migrator = $this; /* @var Migrator $migrator */
        $files = $osm_app[Files::class]; /* @var Files $files */

        if ($migrator->fresh) {
            $files->dropAllFiles();
        }

        $proceed();
    }
}