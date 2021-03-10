<?php

declare(strict_types=1);

namespace Osm\Framework\Logs\Traits;

use Osm\Framework\Logs\Logs;

/**
 * @property Logs $logs
 */
trait AppTrait
{
    /** @noinspection PhpUnused */
    protected function get_logs(): Logs {
        return Logs::new();
    }
}