<?php

declare(strict_types=1);

use Osm\Framework\Console\Module;
use Osm\Framework\Samples\App;
use Osm\Runtime\Apps;

require 'vendor/autoload.php';

Apps::$project_path = dirname(__DIR__);
Apps::compile(App::class);
Apps::run(Apps::create(App::class), function (App $app) {
    $app->console->run();
});
