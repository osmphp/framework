<?php

namespace Osm\Framework\Queues;

use Illuminate\Container\Container;
use Illuminate\Queue\DatabaseQueue;
use Osm\Core\App;
use Osm\Framework\Db\Db;

/**
 * @property Db $db @required
 * @property Container $laravel_container @required
 */
class Database extends Queue
{
    protected function default($property) {
        global $osm_app; /* @var App $osm_app */

        switch ($property) {
            case 'db': return $osm_app->db;
            case 'laravel_container': return $osm_app->laravel->container;
            case 'laravel_queue':
                /* @var DatabaseQueue $queue */
                $queue = $osm_app->createRaw(DatabaseQueue::class, $this->db->connection,
                    'jobs', 'default', 60);
                $queue->setContainer($this->laravel_container);
                return $queue;
        }
        return parent::default($property);
    }

}