<?php

namespace Osm\Framework\Cron\Traits;

use Monolog\Formatter\LineFormatter;
use Monolog\Handler\NullHandler;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Osm\Core\App;
use Osm\Framework\Cron\Jobs;
use Osm\Framework\Logging\Logs;

trait PropertiesTrait
{
    public function Osm_Core_App__cron_jobs(App $app) {
        return $app->cache->remember('cron_jobs', function($data) {
            return Jobs::new($data);
        });
    }

    public function Osm_Framework_Logging_Logs__cron(Logs $logs) {
        global $osm_app; /* @var App $osm_app */

        // create new logging channel
        $logger = new Logger('cron');

        if (!$osm_app->settings->log_cron) {
            return $logger->pushHandler(new NullHandler());
        }

        $logger->pushHandler($handler = new StreamHandler(
            $osm_app->path("{$osm_app->temp_path}/log/cron.log")));

        // write file name and file contents of each reported layer file
//        $handler->setFormatter(new LineFormatter(
//                "# %context.filename% \n\n%extra.contents%\n\n",
//                LineFormatter::SIMPLE_DATE, true
//            )
//        );

        return $logger;
    }
}