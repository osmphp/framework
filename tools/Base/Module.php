<?php

declare(strict_types=1);

namespace Osm\Tools\Base;

use Osm\Core\BaseModule;
use Osm\Tools\App;

class Module extends BaseModule
{
    public static ?string $app_class_name = App::class;

    public static array $requires = [
        \Osm\Framework\Console\Module::class,
    ];
}