<?php

declare(strict_types=1);

namespace Osm\Framework\Env;

use Dotenv\Dotenv;
use Osm\Core\App;
use Osm\Core\Object_;

class Env extends Object_
{
    protected bool $initialized = false;

    protected function default(string $property): mixed {
        if (!$this->initialized) {
            $this->initialize();
        }

        return $_ENV[$property] ?? parent::default($property);
    }

    protected function initialize() {
        global $osm_app; /* @var App $osm_app */

        if (!is_file("{$osm_app->paths->project}/.env.{$osm_app->name}")) {
            return;
        }

        $dotenv = Dotenv::createImmutable($osm_app->paths->project,
            ".env.{$osm_app->name}");
        $dotenv->load();
    }
}