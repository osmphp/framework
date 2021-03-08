<?php

declare(strict_types=1);

namespace Osm\Framework\Db;

use Illuminate\Database\Connection;
use Illuminate\Database\Query\Builder;
use Osm\Core\App;
use Osm\Core\BaseModule;
use Osm\Core\Object_;

/**
 * @property Connection $connection
 * @property Module $module
 * @property array $config
 */
abstract class Db extends Object_
{
    public static ?string $name;

    /** @noinspection PhpUnused */
    protected function get_connection(): Connection {
        return $this->module->connection_factory->make($this->config);
    }

    /** @noinspection PhpUnused */
    protected function get_module(): BaseModule {
        global $osm_app; /* @var App $osm_app */

        return $osm_app->modules[Module::class];
    }

    public function create(string $table, callable $callback): void {
        $this->connection->getSchemaBuilder()->create($table, $callback);
    }

    public function alter(string $table, callable $callback): void {
        $this->connection->getSchemaBuilder()->table($table, $callback);
    }

    public function drop(string $table): void {
        $this->connection->getSchemaBuilder()->drop($table);
    }

    public function query(): Builder {
        return $this->connection->query();
    }

    public function table(string $table, ?string $as = null): Builder {
        return $this->connection->table($table, $as);
    }

    public function beginTransaction(): void {
        $this->connection->beginTransaction();
    }

    public function commit(): void {
        $this->connection->commit();
    }

    public function rollBack(): void {
        $this->connection->rollBack();
    }

    public function transaction(callable $callback, int $attempts = 1): void {
        $this->connection->transaction($callback, $attempts);
    }
}