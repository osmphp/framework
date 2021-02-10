<?php

declare(strict_types=1);

namespace Osm\Framework\Env\Traits;

use Osm\Framework\Env\Env;

/**
 * @property Env $env
 */
trait AppTrait
{
    /** @noinspection PhpUnused */
    protected function get_env(): Env {
        return Env::new();
    }
}