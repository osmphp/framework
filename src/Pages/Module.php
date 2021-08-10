<?php

declare(strict_types=1);

namespace Osm\Framework\Pages;

use Osm\Core\Attributes\Name;
use Osm\Core\BaseModule;

#[Name('std-pages')]
class Module extends BaseModule
{
    public static array $requires = [
        \Osm\Framework\Blade\Module::class,
    ];
}