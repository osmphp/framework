<?php

declare(strict_types=1);

namespace Osm\Framework\Samples\Data;

use Osm\Core\BaseModule;
use Osm\Framework\Data\Data;
use Osm\Framework\Samples\App;

class Module extends BaseModule
{
    public static ?string $app_class_name = App::class;

    public static array $requires = [
        \Osm\Framework\All\Module::class,
    ];

    public static array $traits = [
        Data::class => Traits\DataTrait::class,
    ];
}