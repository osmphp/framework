<?php

declare(strict_types=1);

namespace Osm\Framework\Samples\Base;

use Osm\Core\BaseModule;

class Module extends BaseModule
{
    public static array $requires = [
        \Osm\Framework\Settings\Module::class,
        \Osm\Framework\Cache\Module::class,
        \Osm\Framework\Console\Module::class,
        \Osm\Framework\Translations\Module::class,
        \Osm\Framework\Db\Module::class,
    ];
}