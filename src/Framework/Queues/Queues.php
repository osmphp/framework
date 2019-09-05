<?php

namespace Osm\Framework\Queues;

use Illuminate\Bus\Dispatcher;
use Osm\Core\App;
use Osm\Framework\Data\CollectionRegistry;

/**
 * @property Module $module @required
 * @property Dispatcher $laravel_dispatcher @required
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
        }
        return parent::default($property);
    }

    public function dispatch(Job $job) {
        $this->laravel_dispatcher->dispatch($job);
    }

    public function dispatchNow(Job $job) {
        $this->laravel_dispatcher->dispatchNow($job);
    }
}