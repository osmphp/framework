<?php

declare(strict_types=1);

namespace Osm\Framework\Samples\Js;

use Osm\Core\Attributes\Name;
use Osm\Core\BaseModule;
use Osm\Framework\Samples\App;

#[Name('sample-js')]
class Module extends BaseModule
{
    public static ?string $app_class_name = App::class;

    public static array $requires = [
        \Osm\Framework\All\Module::class,
        \Osm\Framework\Samples\Http\Module::class,
    ];

}