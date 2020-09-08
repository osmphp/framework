<?php

namespace Osm\Framework\Db\Traits;

use Illuminate\Database\Connection;
use Illuminate\Database\QueryException;
use Osm\Core\App;
use Osm\Core\Profiler;
use Osm\Core\Promise;
use Osm\Framework\Db\Logging\QueryLogger;

trait ConnectionTrait
{
    protected function around_runQueryCallback(callable $proceed, $query, $bindings, \Closure $callback)
    {
        global $osm_app; /* @var App $osm_app */
        global $osm_profiler; /* @var Profiler $osm_profiler */

        if ($osm_profiler) $osm_profiler->start($query, 'queries');
        try {
            return $proceed($query, $bindings, $callback);
        }
        catch (QueryException $e) {
            throw new QueryException($query, $this->prepareBindingsForDebugging($bindings), $e->getPrevious());
        }
        finally {
            if ($osm_profiler) $osm_profiler->stop($query);
        }
    }

    public function prepareBindingsForDebugging($bindings) {
        /* @var Connection $db */
        $db = $this;

        $quote = "'";

        foreach ($bindings as &$binding) {
            if ($binding instanceof Promise) {
                $binding = $binding->get();
            }

            if (is_null($binding)) {
                $binding = 'NULL';
            }
            elseif (is_string($binding)) {
                $binding = $quote . $binding . $quote;
            }
        }

        return $db->prepareBindings($bindings);
    }

    protected function around_select(callable $proceed, ...$args) {
        global $osm_app; /* @var App $osm_app */

        /* @var QueryLogger $logger */
        $logger = $osm_app[QueryLogger::class];

        $logger->select = true;

        try {
            return $logger->select = $proceed(...$args);
        }
        finally {
            $logger->log();
        }
    }

    protected function around_bindValues(callable $proceed, $statement, $bindings) {
        global $osm_app; /* @var App $osm_app */

        /* @var QueryLogger $logger */
        $logger = $osm_app[QueryLogger::class];

        if ($logger->select === null) {
            $logger->statement = $statement;
        }

        return $proceed($statement, $bindings);
    }

    protected function around_logQuery(callable $proceed, $query, $bindings, $time) {
        global $osm_app; /* @var App $osm_app */

        /* @var QueryLogger $logger */
        $logger = $osm_app[QueryLogger::class];

        $logger->query = $query;
        $logger->bindings = $bindings;
        $logger->time = $time;
        $logger->db = $this;
        if ($logger->select === null) {
            $logger->log();
        }
    }

}