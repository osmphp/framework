<?php

namespace Osm\Framework\Queues\Commands;

use Illuminate\Contracts\Queue\Job;
use Illuminate\Queue\Events\JobFailed;
use Illuminate\Queue\Events\JobProcessed;
use Illuminate\Queue\Worker;
use Illuminate\Queue\WorkerOptions;
use Osm\Core\App;
use Osm\Framework\Console\Command;
use Osm\Framework\Queues\LaravelJob;
use Osm\Framework\Queues\Module;

/**
 * @property Module $module @required
 * @property Worker $worker @required
 * @property WorkerOptions $worker_options @required
 * @property string $queue @required
 */
class Process extends Command
{
    public function default($property) {
        global $osm_app; /* @var App $osm_app */

        switch ($property) {
            case 'module': return $osm_app->modules['Osm_Framework_Queues'];
            case 'worker': return $osm_app->createRaw(Worker::class,
                $this->module->laravel_manager, $osm_app->laravel->events,
                $osm_app->laravel->exception_handler,
                [$this, 'isDownForMaintenance']);
            case 'worker_options':
                $options = new WorkerOptions();
                $options->stopWhenEmpty = false;
                $options->sleep = 1;
                return $options;
            case 'queue': return $this->input->getArgument('queue');
        }
        return parent::default($property);
    }

    public function run() {
        $this->module->laravel_manager->after(function(JobProcessed $event) {
            $this->output->writeln("{$this->getJobName($event->job)} OK");
        });
        $this->module->laravel_manager->failing(function(JobFailed $event) {
            $this->output->writeln("{$this->getJobName($event->job)} FAIL");
        });

        pcntl_signal(SIGINT, [$this, 'stop']); // Ctrl+C
        pcntl_signal(SIGTSTP, [$this, 'stop']); // Ctrl+Z

        $this->worker->daemon(null, $this->queue, $this->worker_options);
    }

    public function isDownForMaintenance() {
        return false;
    }

    protected function getJobName(Job $job) {
        if (!($payload = json_decode($job->getRawBody()))) {
            return null;
        }

        if (!($laravelJob = unserialize($payload->data->command))) {
            return $payload->displayName;
        }

        if (!($laravelJob instanceof LaravelJob)) {
            return $payload->displayName;
        }

        return $laravelJob->key;
    }

    public function stop() {
        if ($this->module->job) {
            $this->module->job->interrupted();
        }
        exit(0);
    }
}