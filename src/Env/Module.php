<?php

declare(strict_types=1);

namespace Osm\Framework\Env;

use Osm\Core\App;
use Osm\Core\Module as BaseModule;

class Module extends BaseModule
{
    public static array $traits = [
        App::class => Traits\AppTrait::class,
    ];
}