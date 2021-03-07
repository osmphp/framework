<?php

declare(strict_types=1);

namespace Osm\Framework\Db;

use Illuminate\Database\Connectors\ConnectionFactory;
use Osm\Core\App;
use Osm\Core\BaseModule;
use Osm\Framework\Laravel\Module as LaravelModule;

/**
 * @property LaravelModule $laravel
 * @property ConnectionFactory $connection_factory
 */
class Module extends BaseModule
{
    public static array $requires = [
        \Osm\Framework\Env\Module::class,
        LaravelModule::class,
    ];

    public static array $traits = [
        App::class => Traits\AppTrait::class,
    ];

    public static array $classes = [
        ConnectionFactory::class,
    ];

    /** @noinspection PhpUnused */
    protected function get_laravel(): BaseModule {
        global $osm_app; /* @var App $osm_app */

        return $osm_app->modules[LaravelModule::class];
    }

    /** @noinspection PhpUnused */
    protected function get_connection_factory(): object {
        global $osm_app; /* @var App $osm_app */

        return $osm_app->create(ConnectionFactory::class,
            $this->laravel->container);
    }
}