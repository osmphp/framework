<?php

namespace Osm\Framework\Queues;

use Carbon\Carbon;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Osm\Core\App;
use Osm\Core\Exceptions\NotImplemented;
use Osm\Core\Object_;
use Osm\Framework\Db\Db;

/**
 * @property int $job @required @part
 * @property Job $job_
 * @property Db $db @required
 * @property string $key @required
 * @property Module $module @required
 *
 * @property string $log @temp
 */
class LaravelJob extends Object_ implements ShouldQueue
{
    #region Properties
    protected function default($property) {
        global $osm_app; /* @var App $osm_app */

        switch ($property) {
            case 'job_': return $this->getJob();
            case 'db': return $osm_app->db;
            case 'key': return $this->job_
                ? "{$this->job_->class_} ({$this->job_->key})"
                : null;
            case 'module': return $osm_app->modules['Osm_Framework_Queues'];
        }

        return parent::default($property);
    }

    protected function getJob() {
        return ($data = $this->selectJob()->first())
            ? Job::new(array_merge((array)$data, ['laravel_job' => $this]))
            : null;
    }
    #endregion

    public function handle() {
        // if job history record is not there, do nothing. It may be deleted,
        // for instance, by referential integrity rules
        if (!$this->job_) {
            return;
        }

        $this->selectJob()->update(['status' => Job::PROCESSING]);
        $this->log = '';
        $this->module->job = $this;
        $startedAt = microtime(true);
        try {
            $this->job_->handle();
        }
        catch (\Throwable $e) {
            $this->selectJob()->update([
                'elapsed' => microtime(true) - $startedAt,
                'processed_at' => Carbon::now(),
                'status' => Job::FAILED,
                'error' => $e->getMessage(),
                'log' => $this->log,
                'stack_trace' => $e->getTraceAsString(),
            ]);

            throw $e;
        }
        finally {
            $this->module->job = null;
        }

        $this->selectJob()->update([
            'elapsed' => microtime(true) - $startedAt,
            'processed_at' => Carbon::now(),
            'status' => Job::FINISHED,
            'log' => $this->log,
        ]);
    }

    /**
     * @return QueryBuilder
     */
    protected function selectJob() {
        return $this->db->connection->table('jobs')
            ->where('id', '=', $this->job);
    }

    public function interrupted() {
        $this->selectJob()->update([
            'processed_at' => Carbon::now(),
            'status' => Job::INTERRUPTED,
            'log' => $this->log,
        ]);
    }
}