<?php

return [
    'main' => [
        'host' => osm_env('REDIS_HOST', '127.0.0.1'),
        'password' => osm_env('REDIS_PASSWORD'),
        'port' => osm_env('REDIS_PORT', 6379),
        'database' => osm_env('REDIS_DB', 0),
    ],
];