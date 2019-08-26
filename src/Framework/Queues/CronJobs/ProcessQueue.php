<?php

namespace Osm\Framework\Queues\CronJobs;

use Carbon\Carbon;
use Illuminate\Queue\Worker;
use Illuminate\Queue\WorkerOptions;
use Osm\Core\App;
use Osm\Framework\Cron\Job;
use Osm\Framework\Queues\Module;
use Osm\Framework\Queues\Processor;
use Osm\Framework\Settings\Settings;

/**
 * @property Module $module @required
 * @property Worker $worker @required
 * @property WorkerOptions $worker_options @required
 * @property Settings $settings @required
 */
class ProcessQueue extends Job
{
    public function default($property) {
        global $osm_app; /* @var App $osm_app */

        switch ($property) {
            case 'settings': return $osm_app->settings;
            case 'module': return $osm_app->modules['Osm_Framework_Queues'];
            case 'worker': return $osm_app->createRaw(Worker::class, $this->module->laravel_manager,
                $osm_app->laravel->events, $osm_app->laravel->exception_handler);
            case 'worker_options':
                $options = new WorkerOptions();
                $options->stopWhenEmpty = true;
                $options->sleep = 0;
                return $options;
        }
        return parent::default($property);
    }

    public function isScheduledAt(Carbon $time) {
        if ($this->settings->queue_processor != Processor::CRON) {
            return false;
        }
        return parent::isScheduledAt($time);
    }

    public function run() {
        $this->worker->daemon(null, 'default', $this->worker_options);
    }
}