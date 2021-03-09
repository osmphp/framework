<?php

declare(strict_types=1);

namespace Osm\Framework\Samples\Migrations;

use Osm\Core\BaseModule;
use Osm\Framework\Samples\App;

class Module extends BaseModule
{
    public static ?string $app_class_name = App::class;

    public static array $requires = [
        \Osm\Framework\All\Module::class,
    ];
}