<?php

namespace Osm\Framework\Queues;

use Illuminate\Queue\QueueManager;
use Osm\Core\App;

class LaravelManager extends QueueManager
{
    public function connection($name = null) {
        global $osm_app; /* @var App $osm_app */

        $store = $osm_app->queues[$name ?? $osm_app->settings->queue_store];
        return $store->laravel_queue;
    }

    public function isDownForMaintenance() {
        return false;
    }
}