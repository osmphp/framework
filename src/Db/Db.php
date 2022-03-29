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
use Osm\Framework\Db\Exceptions\TransactionError;
use Osm\Framework\Laravel\Module as LaravelModule;
use function Osm\__;

/**
 * @property Connection $connection
 * @property Connection $ddl
 * @property Module $module
 * @property array $config
 */
abstract class Db extends Object_
{
    protected array $committing = [];
    protected array $committed = [];
    protected array $rolled_back = [];
    protected int $transaction_count = 0;
    protected bool $rolling_transaction_back = false;
    protected bool $committing_transaction = false;

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
        if ($this->committing_transaction) {
            throw new TransactionError(__(
                "Can't begin a transaction in a `committing()` callback"));
        }

        if ($this->rolling_transaction_back) {
            throw new TransactionError(__(
                "Can't begin an inner transaction in the outer transaction that is being rolled back."));
        }

        if ($this->transaction_count === 0) {
            $this->connection->beginTransaction();
        }

        $this->transaction_count++;
    }

    public function commit(): void {
        if ($this->rolling_transaction_back) {
            throw new TransactionError(__(
                "Can't commit a transaction that is being rolled back."));
        }

        $this->transaction_count--;
        if ($this->transaction_count > 0) {
            // in inner transaction, do nothing
            return;
        }

        $this->committing_transaction = true;
        try {
            foreach ($this->committing as $callback) {
                $callback($this);
            }
        }
        finally {
            $this->committing_transaction = false;
        }

        $this->connection->commit();

        $committed = $this->committed;
        $this->committing = [];
        $this->committed = [];
        $this->rolled_back = [];
        $this->rolling_transaction_back = false;
        $this->committing_transaction = false;

        foreach ($committed as $callback) {
            $callback($this);
        }
    }

    public function rollBack(): void {
        // if a `committing()` callback fails, the `transaction_count` may
        // be 0, not 1, so the check
        if ($this->transaction_count > 0) {
            $this->transaction_count--;
            $this->rolling_transaction_back = true;
        }

        if ($this->transaction_count > 0) {
            // in inner transaction, do nothing
            return;
        }

        $rolledBack = array_reverse($this->rolled_back);
        $this->committing = [];
        $this->committed = [];
        $this->rolled_back = [];
        $this->rolling_transaction_back = false;
        $this->committing_transaction = false;

        $this->connection->rollBack();

        foreach ($rolledBack as $callback) {
            $callback($this);
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

    public function committing(callable $callback): void {
        $this->committing[] = $callback;
    }

    public function committed(callable $callback): void {
        $this->committed[] = $callback;
    }

    public function rolledBack(callable $callback): void {
        $this->rolled_back[] = $callback;
    }
}