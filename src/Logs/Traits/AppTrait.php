<?php

declare(strict_types=1);

namespace Osm\Framework\Logs\Traits;

use Osm\Core\App;
use Osm\Core\Attributes\UseIn;
use Osm\Framework\Logs\Logs;

/**
 * @property Logs $logs
 */
#[UseIn(App::class)]
trait AppTrait
{
    /** @noinspection PhpUnused */
    protected function get_logs(): Logs {
        return Logs::new();
    }
}