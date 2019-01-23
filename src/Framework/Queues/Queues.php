<?php

namespace Manadev\Framework\Queues;

use Illuminate\Bus\Dispatcher;
use Manadev\Core\App;
use Manadev\Framework\Data\CollectionRegistry;

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
        global $m_app; /* @var App $m_app */

        switch ($property) {
            case 'module': return $m_app->modules['Manadev_Framework_Queues'];
            case 'laravel_dispatcher': return $this->module->laravel_dispatcher;
        }
        return parent::default($property);
    }

    public function dispatch(Job $job) {
        $this->laravel_dispatcher->dispatch($job);
    }
}