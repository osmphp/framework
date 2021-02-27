<?php

declare(strict_types=1);

namespace Osm\Framework\Samples\Cache;

use Osm\Core\BaseModule;
use Osm\Framework\Samples\App;

class Module extends BaseModule
{
    public static ?string $app_class_name = App::class;

    public static array $requires = [
        \Osm\Framework\Cache\Module::class,
    ];
}