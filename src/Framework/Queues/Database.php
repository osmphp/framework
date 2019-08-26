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
        global $m_app; /* @var App $m_app */

        switch ($property) {
            case 'db': return $m_app->db;
            case 'laravel_container': return $m_app->laravel->container;
            case 'laravel_queue':
                /* @var DatabaseQueue $queue */
                $queue = $m_app->createRaw(DatabaseQueue::class, $this->db->connection,
                    'jobs', 'default', 60);
                $queue->setContainer($this->laravel_container);
                return $queue;
        }
        return parent::default($property);
    }

}