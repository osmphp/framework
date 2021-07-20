<?php

declare(strict_types=1);

namespace Osm\Framework\Maintenance;

use Osm\Core\BaseModule;

class Module extends BaseModule
{
    public static array $requires = [
        \Osm\Framework\Console\Module::class,
        \Osm\Framework\Http\Module::class,
    ];
}