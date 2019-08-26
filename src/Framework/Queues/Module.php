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
        global $m_app; /* @var App $m_app */

        switch ($property) {
            case 'laravel_manager': return $m_app->createRaw(LaravelManager::class,
                $m_app->laravel->container);
            case 'laravel_dispatcher': return $m_app->createRaw(Dispatcher::class,
                $m_app->laravel->container, function ($connection = null) {
                    return $this->laravel_manager->connection($connection);
                });
        }
        return parent::default($property);
    }
    public function boot() {
        global $m_app; /* @var App $m_app */

        parent::boot();

        $m_app->laravel->container->bind(CallQueuedHandler::class, function() {
            global $m_app; /* @var App $m_app */

            return $m_app->createRaw(CallQueuedHandler::class, $this->laravel_dispatcher);
        });
    }
}