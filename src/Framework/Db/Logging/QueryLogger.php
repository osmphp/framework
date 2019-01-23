<?php

namespace Manadev\Framework\Db\Logging;

use Illuminate\Database\Connection;
use Illuminate\Log\Logger;
use Illuminate\Support\Str;
use Manadev\Core\App;
use Manadev\Core\Object_;
use Manadev\Framework\Db\Module;
use Manadev\Framework\Settings\Settings;
use Monolog\Handler\AbstractProcessingHandler;
use Monolog\Logger as MonologLogger;

/**
 * @property bool|object[] $select
 * @property \PDOStatement $statement
 * @property string $query
 * @property array $bindings
 * @property float $time
 * @property Connection $db
 *
 * @property Settings $settings @required
 * @property string $filename @required
 * @property AbstractProcessingHandler $handler @required
 * @property Logger $writer @required
 * @property Module $module @required
 * @property QueryLoggingClasses|string[] $query_logging_classes @required
 */
class QueryLogger extends Object_
{
    protected function default($property) {
        global $m_app; /* @var App $m_app */

        switch ($property) {
            case 'settings': return $m_app->settings;
            case 'filename': return $m_app->path("{$m_app->temp_path}/log/queries/" .
                (PHP_SAPI !== 'cli' ? $_SERVER['REMOTE_ADDR'] : 'cli') . '-' . date("Y-m-d-H-i-s") . '.log');
            case 'handler':
                return (new FileHandler($this->filename, MonologLogger::DEBUG))
                    ->setFormatter(new QueryLineFormatter());
            case 'writer':
                $result = new Logger($logger = new MonologLogger('queries'), $m_app->laravel->events);
                $logger->pushHandler($this->handler);
                return $result;
            case 'module': return $m_app->modules['Manadev_Framework_Db'];
            case 'query_logging_classes': return $this->module->query_logging_classes;

        }
        return parent::default($property);
    }

    public function log() {
        try {
            if (!$this->settings->log_db_queries) {
                return;
            }
            if ($this->time < $this->settings->log_db_queries_from) {
                return;
            }
            if (!$this->db) {
                return;
            }

            if ($this->select !== null) {
                $affected = (string)m_(":count row(s) returned", ['count' => count($this->select)]);
            }
            elseif ($this->statement) {
                $affected = (string)m_(":count row(s) affected", ['count' => $this->statement->rowCount()]);
            }
            else {
                $affected = '';
            }

            $query = Str::replaceArray('?', $this->db->prepareBindingsForDebugging($this->bindings),
                $this->query);
            $time = $this->time;
            $this->writer->info($query, array_merge($this->getCallInfo(), compact('time', 'affected')));
        }
        finally {
            $this->reset();
        }
    }

    protected function getCallInfo() {
        $trace = debug_backtrace();
        if (($index = $this->findDbCall($trace)) === null) {
            return compact('trace');
        }

        if (($traceEntry = $this->findCallerTraceEntry($trace, $index)) === null) {
            return compact('trace');
        }

        return [ 'file' => $traceEntry['file'], 'line' => $traceEntry['line'] ];
    }

    protected function findDbCall($trace) {
        for ($index = count($trace) - 1; $index >= 0; $index--) {
            if (!$this->isDbCall($trace[$index])) {
                continue;
            }

            return $index;
        }

        return null;
    }

    protected function isDbCall($traceEntry) {
        if (!isset($traceEntry['object'])) {
            return false;
        }

        foreach ($this->query_logging_classes as $class) {
            if (is_a($traceEntry['object'], $class)) {
                return true;
            }
        }

        return false;
    }

    protected function findCallerTraceEntry($trace, $index) {
        return $trace[$index];
    }

    protected function reset() {
        $this->select = null;
        $this->statement = null;
        $this->query = null;
        $this->bindings = null;
        $this->db = null;
        $this->time = null;
    }
}