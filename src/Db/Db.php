<?php

declare(strict_types=1);

namespace Osm\Framework\Db;

use Illuminate\Database\Connection;
use Illuminate\Database\Events;
use Illuminate\Database\Query\Builder;
use Illuminate\Database\Query\Expression;
use Osm\Core\App;
use Osm\Core\BaseModule;
use Osm\Core\Object_;
use Osm\Framework\Laravel\Module as LaravelModule;

/**
 * @property Connection $connection
 * @property Connection $ddl
 * @property Module $module
 * @property array $config
 */
abstract class Db extends Object_
{
    /**
     * @var callable[]
     */
    protected array $committed = [];
    /**
     * @var callable[]
     */
    protected array $rolledBack = [];
    protected int $transactions = 0;

    protected function get_connection(): Connection {
        return $this->connect();
    }

    protected function get_ddl(): Connection {
        return $this->connect();
    }

    public function connect(): Connection {
        global $osm_app; /* @var App $osm_app */

        $db = $this->module->connection_factory->make($this->config);

        if ($osm_app->settings->logs?->db ?? false) {
            /* @var LaravelModule $laravel */
            $laravel = $osm_app->modules[LaravelModule::class];

            $db->setEventDispatcher($laravel->events);

            $db->listen(function (Events\QueryExecuted $query) use ($osm_app) {
                $osm_app->logs->db->info($query->sql, [
                    'bindings' => $query->bindings,
                    'time' => $query->time,
                ]);
            });
        }

        return $db;
    }

    /** @noinspection PhpUnused */
    protected function get_module(): BaseModule {
        global $osm_app; /* @var App $osm_app */

        return $osm_app->modules[Module::class];
    }

    public function create(string $table, callable $callback): void {
        $this->ddl->getSchemaBuilder()->create($table, $callback);
    }

    public function alter(string $table, callable $callback): void {
        $this->ddl->getSchemaBuilder()->table($table, $callback);
    }

    public function drop(string $table): void {
        $this->ddl->getSchemaBuilder()->drop($table);
    }

    public function dropIfExists(string $table): void {
        $this->ddl->getSchemaBuilder()->dropIfExists($table);
    }

    public function query(): Builder {
        return $this->connection->query();
    }

    public function table(string $table, ?string $as = null): Builder {
        return $this->connection->table($table, $as);
    }

    public function beginTransaction(): void {
        $this->connection->beginTransaction();
        $this->transactions++;
    }

    public function commit(): void {
        $this->connection->commit();
        $this->transactions--;
        if (!$this->transactions) {
            $callbacks = $this->committed;
            $this->committed = [];
            $this->rolledBack = [];

            foreach ($callbacks as $callback) {
                $callback($this);
            }
        }
    }

    public function rollBack(): void {
        $this->connection->rollBack();
        $this->transactions--;
        if (!$this->transactions) {
            $callbacks = array_reverse($this->rolledBack);
            $this->committed = [];
            $this->rolledBack = [];

            foreach ($callbacks as $callback) {
                $callback($this);
            }
        }
    }

    public function transaction(callable $callback): mixed {
        $this->beginTransaction();

        try {
            $result = $callback();
            $this->commit();
            return $result;
        }
        catch (\Throwable $e) {
            $this->rollBack();
            throw $e;
        }
    }

    public function dryRun(callable $callback): mixed {
        $this->beginTransaction();

        try {
            return $callback();
        }
        finally {
            $this->rollBack();
        }
    }


    public function exists(string $table): bool {
        return $this->connection->getSchemaBuilder()->hasTable($table);
    }

    public function raw(string $expr): Expression {
        return $this->connection->raw($expr);
    }

    public function committed(callable $callback): void {
        $this->committed[] = $callback;
    }

    public function rolledBack(callable $callback): void {
        $this->rolledBack[] = $callback;
    }
}