<?php

namespace Osm\Framework\Cache\Commands;

use Osm\Core\App;
use Osm\Framework\Console\Command;
use function Osm\delete_dir;

class Refresh extends Command
{
    public string $name = 'refresh';

    public function run(): void
    {
        global $osm_app; /* @var App $osm_app */

        $osm_app->cache->clear();

        delete_dir("{$osm_app->paths->temp}/view_cache");
    }
}