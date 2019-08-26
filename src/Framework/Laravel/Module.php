<?php

namespace Osm\Framework\Laravel;

use Illuminate\Container\Container;
use Illuminate\Database\Connectors\ConnectionFactory;
use Illuminate\Events\Dispatcher;
use Illuminate\Filesystem\Filesystem;
use Osm\Core\App;
use Osm\Core\Modules\BaseModule;

/**
 * @property Container $container
 * @property Dispatcher $events
 * @property Console $console
 * @property Filesystem $files
 * @property ConnectionFactory $db
 * @property ExceptionHandler $exception_handler
 */
class Module extends BaseModule
{
    public $short_name = 'laravel';

    public function default($property) {
        global $m_app; /* @var App $m_app */

        switch ($property) {
            case 'container': return $m_app->createRaw(Container::class);
            case 'events': return $m_app->createRaw(Dispatcher::class);
            case 'console': return $m_app->createRaw(Console::class,
                $this->container, $this->events, $m_app->settings->app_version);
            case 'files': return $m_app->createRaw(Filesystem::class);
            case 'db': return $m_app->createRaw(ConnectionFactory::class, $this->container);
            case 'exception_handler': return $m_app->createRaw(ExceptionHandler::class);
        }

        return parent::default($property);
    }
}