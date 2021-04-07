<?php

declare(strict_types=1);

namespace Osm\Framework\Areas;

use Osm\Core\BaseModule;

class Module extends BaseModule
{
    public static array $requires = [
        \Osm\Framework\Cache\Module::class,
    ];
}