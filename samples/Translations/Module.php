<?php

declare(strict_types=1);

namespace Osm\Framework\Samples\Translations;

use Osm\Core\Module as BaseModule;
use Osm\Framework\Samples\App;

class Module extends BaseModule
{
    public static ?string $app_class_name = App::class;

    public static array $requires = [
        \Osm\Framework\Translations\Module::class,
    ];
}