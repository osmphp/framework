<?php

declare(strict_types=1);

namespace Osm\Framework\Http\Traits;

use Monolog\Handler\RotatingFileHandler;
use Monolog\Logger;
use Osm\Core\App;
use Osm\Core\Attributes\UseIn;
use Osm\Framework\Logs\Logs;

/**
 * @property Logger $http
 */
#[UseIn(Logs::class)]
trait LogsTrait
{
    protected function get_http(): Logger {
        global $osm_app; /* @var App $osm_app */

        $logger = new Logger('default');
        $logger->pushHandler(new RotatingFileHandler(
            "{$osm_app->paths->temp}/logs/http.log"));

        return $logger;
    }
}