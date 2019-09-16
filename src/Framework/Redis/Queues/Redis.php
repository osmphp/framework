<?php

namespace Osm\Framework\Redis\Queues;

use Illuminate\Queue\RedisQueue;
use Osm\Core\App;
use Osm\Framework\Queues\Queue;
use Osm\Framework\Redis\Module;

/**
 * @property int $block_for @part
 * @property string $redis_connection @part
 * @property Module $module @required
 */
class Redis extends Queue
{
    protected function default($property) {
        global $osm_app; /* @var App $osm_app */

        switch ($property) {
            case 'module': return $osm_app->modules['Osm_Framework_Redis'];
            case 'redis_connection': return 'main';
            case 'laravel_queue':
                /* @var RedisQueue $queue */
                $queue = $osm_app->createRaw(RedisQueue::class,
                    $this->module->redis, $this->default,
                    $this->redis_connection, $this->retry_after,
                    $this->block_for);
                $queue->setContainer($osm_app->laravel->container);
                return $queue;
        }
        return parent::default($property);
    }
}