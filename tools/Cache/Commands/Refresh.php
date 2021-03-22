<?php

/** @noinspection PhpUnusedAliasInspection */
declare(strict_types=1);

namespace Osm\Tools\Cache\Commands;

use Osm\Core\App;
use Osm\Framework\Console\Command;
use Osm\Framework\Console\Attributes\Option;
use Osm\Runtime\Apps;

/**
 * @property ?string $app #[Option]
 */
class Refresh extends Command
{
    public string $name = 'refresh';

    public function run(): void {
        Apps::run(Apps::create($this->app), function(App $app) {
            if ($app->cache) {
                $app->cache->clear();
            }
        });
    }
}