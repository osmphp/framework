<?php

use Osm\Framework\Db\MySql;

return [
    'main' => [
        'class' => MySql::class,
        'host' => m_env('DB_HOST', 'localhost'),
        'port' => m_env('DB_PORT', '3306'),
        'database' => m_env('DB_NAME', ''),
        'username' => m_env('DB_USER', ''),
        'password' => m_env('DB_PASSWORD', ''),
    ],
];