<?php

declare(strict_types=1);

namespace Osm\Framework\Env\Traits;

use Osm\Core\App;
use Osm\Core\Paths;

/**
 * @property string $env
 * @property string $env_file
 */
trait PathsTrait
{
    /** @noinspection PhpUnused */
    protected function get_env(): string {
        /* @var Paths $this */
        return $this->project;
    }

    /** @noinspection PhpUnused */
    protected function get_env_file(): string {
        global $osm_app; /* @var App $osm_app */

        return ".env.{$osm_app->name}";
    }
}