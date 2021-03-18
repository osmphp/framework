<?php

declare(strict_types=1);

namespace Osm\Framework\Browser;

use Osm\Core\App;
use Osm\Core\BaseModule;

class Module extends BaseModule
{
    public static array $requires = [
        \Osm\Framework\Http\Module::class,
    ];

    public static array $traits = [
        App::class => Traits\AppTrait::class,
    ];
}