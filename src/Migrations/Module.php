<?php

declare(strict_types=1);

namespace Osm\Framework\Migrations;

use Osm\Core\App;
use Osm\Core\BaseModule;

class Module extends BaseModule
{
    public static array $requires = [
        \Osm\Framework\Db\Module::class,
    ];

    public static array $traits = [
        App::class => Traits\AppTrait::class,
    ];
}