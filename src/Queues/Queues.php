<?php

namespace Osm\Framework\Queues;

use Carbon\Carbon;
use Illuminate\Bus\Dispatcher;
use Osm\Core\App;
use Osm\Core\Exceptions\NotImplemented;
use Osm\Framework\Data\CollectionRegistry;
use Osm\Framework\Db\Db;

/**
 * @property Module $module @required
 * @property Dispatcher $laravel_dispatcher @required
 * @property Db $db @required
 */
class Queues extends CollectionRegistry
{
    public $class_ = Queue::class;
    public $config = 'queues';
    public $not_found_message = "Queue store ':name' not found";

    protected function default($property) {
        global $osm_app; /* @var App $osm_app */

        switch ($property) {
            case 'module': return $osm_app->modules['Osm_Framework_Queues'];
            case 'laravel_dispatcher': return $this->module->laravel_dispatcher;
            case 'db': return $osm_app->db;
        }
        return parent::default($property);
    }

    public function dispatch(Job $job) {
        if ($laravelJob = $this->createJob($job)) {
            $this->laravel_dispatcher->dispatch($laravelJob);
        }
    }

    public function run(Job $job) {
        if ($laravelJob = $this->createJob($job)) {
            $this->laravel_dispatcher->dispatchNow($laravelJob);
        }
    }

    protected function createJob(Job $job) {
        return $this->db->connection->transaction(function () use ($job) {
            if ($job->singleton) {
                $query = $this->db->connection->table('jobs')
                    ->where('status', '=', Job::PENDING);

                if ($job->key !== null) {
                    $query->where('key', '=', $job->key);
                }

                if ($query->exists()) {
                    return null;
                }
            }

            $data = $job->toArray();
            if ($job->key !== null) {
                $data['key'] = (string)$job->key;
            }
            $data['class'] = $job->class_;
            $data['registered_at'] = Carbon::now();

            $id = $this->db->connection->table('jobs')->insertGetId($data);

            return LaravelJob::new(['job' => $id, 'queue' => $job->queue]);
        });
    }
}