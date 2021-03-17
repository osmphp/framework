<?php

declare(strict_types=1);

namespace Osm\Framework\Http;

use Osm\Core\App;
use Osm\Core\BaseModule;

class Module extends BaseModule
{
    public static array $traits = [
        App::class => Traits\AppTrait::class,
    ];
}