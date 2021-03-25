<?php

declare(strict_types=1);

namespace Osm\Framework\Laravel;

use Illuminate\Container\Container;
use Illuminate\Events\Dispatcher;
use Illuminate\Contracts\Events\Dispatcher as DispatcherContract;
use Illuminate\Filesystem\Filesystem;
use Osm\Core\App;
use Osm\Core\BaseModule;

/**
 * @property Container $container
 * @property Dispatcher $events
 * @property Filesystem $files
 */
class Module extends BaseModule
{
    public static array $classes = [
        Container::class,
        Dispatcher::class,
        Filesystem::class,
    ];

    /** @noinspection PhpUnused */
    protected function get_container(): Container {
        global $osm_app; /* @var App $osm_app */

        /* @var Container $container */
        $container = $osm_app->create(Container::class);

        $container['events'] = $this->events;
        $container->bind(DispatcherContract::class, function() {
            return $this->events;
        });

        Container::setInstance($container);

        return $container;
    }

    /** @noinspection PhpUnused */
    protected function get_events(): object {
        global $osm_app; /* @var App $osm_app */

        return $osm_app->create(Dispatcher::class);
    }

    /** @noinspection PhpUnused */
    protected function get_files(): object {
        global $osm_app; /* @var App $osm_app */

        return $osm_app->create(Filesystem::class);
    }
}