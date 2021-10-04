<?php

declare(strict_types=1);

namespace Osm\Framework\Db\Traits;

use Monolog\Handler\RotatingFileHandler;
use Monolog\Logger;
use Osm\Core\App;
use Osm\Core\Attributes\UseIn;
use Osm\Framework\Logs\Logs;

/**
 * @property Logger $db
 */
#[UseIn(Logs::class)]
trait LogsTrait
{
    /** @noinspection PhpUnused */
    protected function get_db(): Logger {
        global $osm_app; /* @var App $osm_app */

        $logger = new Logger('db');
        if ($osm_app->settings->logs?->db ?? false) {
            $logger->pushHandler(new RotatingFileHandler(
                "{$osm_app->paths->temp}/logs/db.log"));
        }

        return $logger;
    }
}