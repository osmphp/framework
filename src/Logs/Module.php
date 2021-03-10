<?php

declare(strict_types=1);

namespace Osm\Framework\Logs;

use Monolog\Handler\Handler;
use Osm\Core\App;
use Osm\Core\BaseModule;
use Osm\Framework\Settings\Hints\Settings;

class Module extends BaseModule
{
    public static array $traits = [
        App::class => Traits\AppTrait::class,
        Settings::class => Traits\SettingsTrait::class,
    ];

    public static array $requires = [
        \Osm\Framework\Settings\Module::class,
    ];
}