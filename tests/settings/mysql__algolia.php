<?php

declare(strict_types=1);

/* @see \Osm\Framework\Settings\Hints\Settings */
return \Osm\merge((object)[
    'db' => [
        'driver' => 'mysql',
        'url' => $_SERVER['MYSQL_DATABASE_URL'] ?? null,
        'host' => $_SERVER['MYSQL_HOST'] ?? 'localhost',
        'port' => $_SERVER['MYSQL_PORT'] ?? '3306',
        'database' => "{$_SERVER['MYSQL_DATABASE']}_test",
        'username' => $_SERVER['MYSQL_USERNAME'],
        'password' => $_SERVER['MYSQL_PASSWORD'],
        'unix_socket' => $_SERVER['MYSQL_SOCKET'] ?? '',
        'charset' => 'utf8mb4',
        'collation' => 'utf8mb4_unicode_ci',
        'prefix' => '',
        'prefix_indexes' => true,
        'strict' => true,
        'engine' => null,
        'options' => extension_loaded('pdo_mysql') ? array_filter([
            PDO::MYSQL_ATTR_SSL_CA => env('MYSQL_ATTR_SSL_CA'),
        ]) : [],
    ],
    'search' => [
        'driver' => 'algolia',
        'index_prefix' => $_SERVER['SEARCH_INDEX_PREFIX'],
        'app_id' => $_SERVER['ALGOLIA_APP_ID'],
        'admin_api_key' => $_SERVER['ALGOLIA_ADMIN_API_KEY'],
        'wait' => true, // testing-only: index new data immediately
    ],
], include __DIR__ . '/general.php');