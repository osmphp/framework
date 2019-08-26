<?php

namespace Osm\Framework\Queues;

use Illuminate\Bus\Dispatcher;
use Illuminate\Queue\CallQueuedHandler;
use Osm\Core\App;
use Osm\Core\Modules\BaseModule;
use Osm\Core\Properties;

/**
 * @property LaravelManager $laravel_manager @required
 * @property Dispatcher $laravel_dispatcher @required
 */
class Module extends BaseModule
{
    public $hard_dependencies = [
        'Osm_Framework_Laravel',
        'Osm_Framework_Settings',
    ];

    public $traits = [
        Properties::class => Traits\PropertiesTrait::class,
    ];

    protected function default($property) {
        global $osm_app; /* @var App $osm_app */

        switch ($property) {
            case 'laravel_manager': return $osm_app->createRaw(LaravelManager::class,
                $osm_app->laravel->container);
            case 'laravel_dispatcher': return $osm_app->createRaw(Dispatcher::class,
                $osm_app->laravel->container, function ($connection = null) {
                    return $this->laravel_manager->connection($connection);
                });
        }
        return parent::default($property);
    }
    public function boot() {
        global $osm_app; /* @var App $osm_app */

        parent::boot();

        $osm_app->laravel->container->bind(CallQueuedHandler::class, function() {
            global $osm_app; /* @var App $osm_app */

            return $osm_app->createRaw(CallQueuedHandler::class, $this->laravel_dispatcher);
        });
    }
}