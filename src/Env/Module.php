<?php

declare(strict_types=1);

namespace Osm\Framework\Env;

use Dotenv\Dotenv;
use Osm\Core\App;
use Osm\Core\BaseModule;
use Osm\Core\Paths;

class Module extends BaseModule
{
    public static array $traits = [
        Paths::class => Traits\PathsTrait::class,
    ];

    public function boot(): void {
        global $osm_app; /* @var App $osm_app */

        if (!is_file("{$osm_app->paths->env}/{$osm_app->paths->env_file}")) {
            return;
        }

        $dotenv = Dotenv::createImmutable($osm_app->paths->env,
            $osm_app->paths->env_file);
        $dotenv->load();
    }
}