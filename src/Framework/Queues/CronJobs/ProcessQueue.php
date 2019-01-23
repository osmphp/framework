<?php

namespace Manadev\Framework\Queues\CronJobs;

use Carbon\Carbon;
use Illuminate\Queue\Worker;
use Illuminate\Queue\WorkerOptions;
use Manadev\Core\App;
use Manadev\Framework\Cron\Job;
use Manadev\Framework\Queues\Module;
use Manadev\Framework\Queues\Processor;
use Manadev\Framework\Settings\Settings;

/**
 * @property Module $module @required
 * @property Worker $worker @required
 * @property WorkerOptions $worker_options @required
 * @property Settings $settings @required
 */
class ProcessQueue extends Job
{
    public function default($property) {
        global $m_app; /* @var App $m_app */

        switch ($property) {
            case 'settings': return $m_app->settings;
            case 'module': return $m_app->modules['Manadev_Framework_Queues'];
            case 'worker': return $m_app->createRaw(Worker::class, $this->module->laravel_manager,
                $m_app->laravel->events, $m_app->laravel->exception_handler);
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