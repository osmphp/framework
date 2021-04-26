<?php

declare(strict_types=1);

namespace Osm\Framework\Db;

use Illuminate\Database\Connectors\ConnectionFactory;
use Osm\Core\App;
use Osm\Core\BaseModule;
use Osm\Framework\Laravel\Module as LaravelModule;
use Osm\Framework\Logs\Hints\LogSettings;
use Osm\Framework\Logs\Logs;
use Osm\Framework\PhpUnit\TestCase;
use Osm\Framework\Settings\Hints\Settings;

/**
 * @property LaravelModule $laravel
 * @property ConnectionFactory $connection_factory
 */
class Module extends BaseModule
{
    public static array $requires = [
        \Osm\Framework\Cache\Module::class,
        \Osm\Framework\Logs\Module::class,
        \Osm\Framework\PhpUnit\Module::class,
        \Osm\Framework\Settings\Module::class,
        LaravelModule::class,
    ];

    public static array $traits = [
        App::class => Traits\AppTrait::class,
        Settings::class => Traits\SettingsTrait::class,
        LogSettings::class => Traits\LogSettingsTrait::class,
        Logs::class => Traits\LogsTrait::class,
        TestCase::class => Traits\TestCaseTrait::class,
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