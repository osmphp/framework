<?php

declare(strict_types=1);

use Osm\Runtime\Apps;
use Osm\Tools\App;

require 'vendor/autoload.php';

Apps::$project_path = dirname(__DIR__);
Apps::run(Apps::create(App::class), function (App $app) {
    $app->console->run();
});
