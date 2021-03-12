<?php

declare(strict_types=1);

namespace Osm\Framework\Data;

use Osm\Core\App;
use Osm\Core\BaseModule;

class Module extends BaseModule
{
    public static array $requires = [
        \Osm\Framework\Cache\Module::class,
        \Osm\Framework\Db\Module::class,
        \Osm\Framework\Search\Module::class,
    ];

    public static array $traits = [
        App::class => Traits\AppTrait::class,
    ];
}