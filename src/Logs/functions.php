<?php

declare(strict_types=1);

namespace Osm {

    use Monolog\Logger;
    use Osm\Core\App;

    function log(string $name = 'default'): Logger {
        global $osm_app; /* @var App $osm_app */

        return $osm_app->logs->{$name};
    }
}