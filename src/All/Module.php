<?php

declare(strict_types=1);

namespace Osm\Framework\All;

use Osm\Core\BaseModule;

class Module extends BaseModule
{
    public static array $requires = [
        \Osm\Framework\AlgoliaSearch\Module::class,
        \Osm\Framework\Areas\Module::class,
        \Osm\Framework\Blade\Module::class,
        \Osm\Framework\Cache\Module::class,
        \Osm\Framework\Console\Module::class,
        \Osm\Framework\Db\Module::class,
        \Osm\Framework\ElasticSearch\Module::class,
        \Osm\Framework\Env\Module::class,
        \Osm\Framework\Http\Module::class,
        \Osm\Framework\Js\Module::class,
        \Osm\Framework\Laravel\Module::class,
        \Osm\Framework\Logs\Module::class,
        \Osm\Framework\Migrations\Module::class,
        \Osm\Framework\Maintenance\Module::class,
        \Osm\Framework\Pages\Module::class,
        \Osm\Framework\Paths\Module::class,
        \Osm\Framework\Search\Module::class,
        \Osm\Framework\Settings\Module::class,
        \Osm\Framework\Themes\Module::class,
        \Osm\Framework\Translations\Module::class,
    ];
}