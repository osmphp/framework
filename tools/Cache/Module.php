<?php

declare(strict_types=1);

namespace Osm\Tools\Cache;

use Osm\Core\BaseModule;
use Osm\Tools\App;

class Module extends BaseModule
{
    public static ?string $app_class_name = App::class;

    public static array $requires = [
        \Osm\Tools\Base\Module::class,
    ];
}