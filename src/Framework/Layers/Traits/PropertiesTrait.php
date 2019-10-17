<?php

namespace Osm\Framework\Layers\Traits;

use Osm\Core\App;
use Osm\Framework\Logging\Logs;
use Monolog\Formatter\LineFormatter;
use Monolog\Handler\NullHandler;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;

trait PropertiesTrait
{
    public function Osm_Framework_Logging_Logs__layers(Logs $logs) {
        global $osm_app; /* @var App $osm_app */

        // create new logging channel
        $logger = new Logger('layers');

        if (!$osm_app->settings->log_layers) {
            return $logger->pushHandler(new NullHandler());
        }

        // write each included layer file to temp/ENV/log/layers/UNIQUE_FILENAME.log
        $logger->pushHandler($handler = new StreamHandler($osm_app->path(
            "{$osm_app->temp_path}/log/layers/{$logs->unique_filename}")));

        // write file name and file contents of each reported layer file
        $handler->setFormatter(new LineFormatter("# %context.filename% \n\n%extra.contents%\n\n",
            LineFormatter::SIMPLE_DATE, true));

        // as user code only provides file name, add file contents to log record using processor
        $logger->pushProcessor(function($record) use ($osm_app) {
            $record['extra']['contents'] = file_get_contents($record['context']['filename']);

            return $record;
        });

        return $logger;
    }

}