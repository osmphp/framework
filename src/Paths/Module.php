<?php

declare(strict_types=1);

namespace Osm\Framework\Paths;

use Osm\Core\BaseModule;
use Osm\Core\Paths;

class Module extends BaseModule
{
    public static array $traits = [
        Paths::class => Traits\PathsTrait::class,
    ];
}