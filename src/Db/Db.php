<?php

declare(strict_types=1);

namespace Osm\Framework\Db;

use Illuminate\Database\Connection;
use Osm\Core\App;
use Osm\Core\BaseModule;
use Osm\Core\Object_;

/**
 * @property string $env_prefix
 * @property Connection $connection
 * @property Module $module
 * @property array $config
 */
abstract class Db extends Object_
{
    /** @noinspection PhpUnused */
    protected function get_connection(): Connection {
        return $this->module->connection_factory->make($this->config);
    }

    /** @noinspection PhpUnused */
    abstract protected function get_config(): array;

    /** @noinspection PhpUnused */
    protected function get_module(): BaseModule {
        global $osm_app; /* @var App $osm_app */

        return $osm_app->modules[Module::class];
    }
}