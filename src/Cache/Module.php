<?php

declare(strict_types=1);

namespace Osm\Framework\Cache;

use Osm\Core\App;
use Osm\Core\BaseModule;
use Osm\Core\Object_;
use Osm\Core\Paths;

class Module extends BaseModule
{
    public static array $requires = [
        \Osm\Framework\Env\Module::class,
        \Osm\Framework\Console\Module::class,
    ];
}