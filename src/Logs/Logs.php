<?php

declare(strict_types=1);

namespace Osm\Framework\Logs;

use Monolog\Handler\RotatingFileHandler;
use Monolog\Logger;
use Osm\Core\App;
use Osm\Core\Object_;

/**
 * @property Logger $default
 */
class Logs extends Object_
{
    /** @noinspection PhpUnused */
    protected function get_elastic(): Logger {
        global $osm_app; /* @var App $osm_app */

        $logger = new Logger('default');
        if ($osm_app->settings->logs?->elastic ?? false) {
            $logger->pushHandler(new RotatingFileHandler(
                "{$osm_app->paths->temp}/logs/default.log"));
        }

        return $logger;
    }
}