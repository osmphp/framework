<?php

declare(strict_types=1);

namespace Osm\Framework\ElasticSearch\Traits;

use Monolog\Handler\RotatingFileHandler;
use Monolog\Logger;
use Osm\Core\App;
use Osm\Core\Attributes\UseIn;
use Osm\Framework\Logs\Logs;

/**
 * @property Logger $elastic
 */
#[UseIn(Logs::class)]
trait LogsTrait
{
    /** @noinspection PhpUnused */
    protected function get_elastic(): Logger {
        global $osm_app; /* @var App $osm_app */

        $logger = new Logger('elastic');
        if ($osm_app->settings->logs?->elastic ?? false) {
            $logger->pushHandler(new RotatingFileHandler(
                "{$osm_app->paths->temp}/logs/elastic.log"));
        }

        return $logger;
    }
}