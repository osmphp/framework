<?php

declare(strict_types=1);

use Osm\Runtime\Apps;
use function Osm\handle_errors;

require 'vendor/autoload.php';
umask(0);
handle_errors();

try {
    Apps::$project_path = dirname(dirname(__DIR__));
    Apps::compile(\Osm\Framework\Samples\App::class);
}
catch (Throwable $e) {
    echo "{$e->getMessage()}\n{$e->getTraceAsString()}\n";
    throw $e;
}
