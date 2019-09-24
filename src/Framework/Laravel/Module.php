<?php

namespace Osm\Framework\Laravel;

use Illuminate\Container\Container;
use Illuminate\Contracts\Events\Dispatcher as DispatcherContract;
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
        global $osm_app; /* @var App $osm_app */

        switch ($property) {
            case 'container': return $this->getContainer();
            case 'events': return $osm_app->createRaw(Dispatcher::class);
            case 'console': return $this->getConsole();
            case 'files': return $osm_app->createRaw(Filesystem::class);
            case 'db': return $osm_app->createRaw(ConnectionFactory::class, $this->container);
            case 'exception_handler': return $osm_app->createRaw(ExceptionHandler::class);
        }

        return parent::default($property);
    }

    protected function getConsole() {
        global $osm_app; /* @var App $osm_app */

        /* @var Console $result */
        $result = $osm_app->createRaw(Console::class, $this->container,
            $this->events, $osm_app->settings->app_version);

        $result->setCatchExceptions(true);

        return $result;
    }

    public function getContainer() {
       global $osm_app; /* @var App $osm_app */

       /* @var Container $result */
       $result = $osm_app->createRaw(Container::class);
       $result['events'] = $this->events;
       $result->bind(DispatcherContract::class, function() {
           return $this->events;
       });
       return $result;
    }
}