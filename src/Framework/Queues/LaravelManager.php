<?php

namespace Manadev\Framework\Queues;

use Illuminate\Queue\QueueManager;
use Manadev\Core\App;

class LaravelManager extends QueueManager
{
    public function connection($name = null) {
        global $m_app; /* @var App $m_app */

        $store = $m_app->queues[$name ?? $m_app->settings->queue_store];
        return $store->laravel_queue;
    }

    public function isDownForMaintenance() {
        return false;
    }
}