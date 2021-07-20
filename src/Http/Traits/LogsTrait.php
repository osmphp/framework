<?php

declare(strict_types=1);

namespace Osm\Framework\Http\Traits;

use Monolog\Handler\RotatingFileHandler;
use Monolog\Logger;
use Osm\Core\App;

/**
 * @property Logger $http
 */
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