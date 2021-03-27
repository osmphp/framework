<?php

declare(strict_types=1);

namespace Osm\Framework\Blade;

use Osm\Core\BaseModule;
use Osm\Framework\Themes\Theme;

class Module extends BaseModule
{
    public static array $requires = [
        \Osm\Framework\Cache\Module::class,
        \Osm\Framework\Themes\Module::class,
    ];

    public static array $traits = [
        Theme::class => Traits\ThemeTrait::class,
    ];
}