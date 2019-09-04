<?php

namespace Osm\Framework\Queues\Commands;

use Illuminate\Queue\Worker;
use Illuminate\Queue\WorkerOptions;
use Osm\Core\App;
use Osm\Framework\Console\Command;
use Osm\Framework\Queues\Module;

/**
 * @property Module $module @required
 * @property Worker $worker @required
 * @property WorkerOptions $worker_options @required
 */
class Process extends Command
{
    public function default($property) {
        global $osm_app; /* @var App $osm_app */

        switch ($property) {
            case 'module': return $osm_app->modules['Osm_Framework_Queues'];
            case 'worker': return $osm_app->createRaw(Worker::class, $this->module->laravel_manager,
                $osm_app->laravel->events, $osm_app->laravel->exception_handler);
            case 'worker_options':
                $options = new WorkerOptions();
                $options->stopWhenEmpty = false;
                $options->sleep = 0;
                return $options;
        }
        return parent::default($property);
        // test 2
    }

    public function run() {
        $this->output->writeln("started");
        $this->module->laravel_manager->before(function() {
            $this->output->writeln("before");
        });
        $this->module->laravel_manager->after(function() {
            $this->output->writeln("after");
        });
        $this->worker->daemon(null, 'default', $this->worker_options);
    }
}