<?php

namespace Osm\Framework\Redis;

use Illuminate\Redis\RedisManager;
use Osm\Core\App;
use Osm\Core\Modules\BaseModule;

/**
 * @property RedisManager $redis @required
 * @property Connections $connections @required @part
 */
class Module extends BaseModule
{
    public $hard_dependencies = [
        'Osm_Framework_Cache',
        'Osm_Framework_Queues',

    ];

    protected function default($property) {
        global $osm_app; /* @var App $osm_app */

        switch ($property) {
            case 'connections': return $osm_app->caches['file']->remember(
            "redis", function($data) {
                return Connections::new($data);
            });

            case 'redis': return $osm_app->createRaw(RedisManager::class,
                $osm_app->laravel->container,
                $osm_app->settings->redis_driver,
                $this->connections->items);
        }

        return parent::default($property);
    }
}