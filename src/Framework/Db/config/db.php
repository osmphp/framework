<?php

use Osm\Framework\Db\MySql;

return [
    'main' => [
        'class' => MySql::class,
        'host' => osm_env('DB_HOST', 'localhost'),
        'port' => osm_env('DB_PORT', '3306'),
        'database' => osm_env('DB_NAME'),
        'username' => osm_env('DB_USER'),
        'password' => osm_env('DB_PASSWORD'),
    ],
];