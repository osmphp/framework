<?php

declare(strict_types=1);

namespace Osm\Framework\Settings;

use Osm\Core\App;
use Osm\Core\BaseModule;
use Osm\Core\Paths;

class Module extends BaseModule
{
    public static array $requires = [
        \Osm\Framework\Env\Module::class,
        \Osm\Framework\Cache\Module::class,
    ];
}