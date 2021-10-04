<?php

declare(strict_types=1);

namespace Osm\Framework\Search;

use Osm\Core\App;
use Osm\Core\BaseModule;
use Osm\Framework\Settings\Hints\Settings;

class Module extends BaseModule
{
    public static array $requires = [
        \Osm\Framework\Settings\Module::class,
        \Osm\Framework\Cache\Module::class,
    ];
}