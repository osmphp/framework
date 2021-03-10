<?php

/** @noinspection PhpUnusedAliasInspection */
declare(strict_types=1);

namespace Osm\Framework\Db;

use Illuminate\Database\Connectors\ConnectionFactory;
use Osm\Core\App;
use Osm\Core\BaseModule;
use Osm\Framework\ElasticSearch\Traits\LogSettingsTrait;
use Osm\Framework\Laravel\Module as LaravelModule;
use Osm\Framework\Logs\Hints\LogSettings;
use Osm\Framework\Logs\Logs;
use Osm\Framework\Settings\Hints\Settings;
use function Osm\get_descendant_classes_by_name;
use Osm\Framework\Cache\Attributes\Cached;

/**
 * @property LaravelModule $laravel
 * @property ConnectionFactory $connection_factory
 * @property string[] $db_classes #[Cached('db_classes')]
 */
class Module extends BaseModule
{
    public static array $requires = [
        \Osm\Framework\Settings\Module::class,
        \Osm\Framework\Logs\Module::class,
        LaravelModule::class,
    ];

    public static array $traits = [
        App::class => Traits\AppTrait::class,
        Settings::class => Traits\SettingsTrait::class,
        LogSettings::class => Traits\LogSettingsTrait::class,
        Logs::class => Traits\LogsTrait::class,
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

    /** @noinspection PhpUnused */
    protected function get_db_classes(): array {
        return get_descendant_classes_by_name(Db::class);
    }
}