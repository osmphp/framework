<?php

namespace Manadev\Framework\Queues\Commands;

use Illuminate\Queue\Worker;
use Illuminate\Queue\WorkerOptions;
use Manadev\Core\App;
use Manadev\Framework\Console\Command;
use Manadev\Framework\Queues\Module;

/**
 * @property Module $module @required
 * @property Worker $worker @required
 * @property WorkerOptions $worker_options @required
 */
class Process extends Command
{
    public function default($property) {
        global $m_app; /* @var App $m_app */

        switch ($property) {
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
        // test 2
    }

    public function run() {
        $this->worker->daemon(null, 'default', $this->worker_options);
    }
}