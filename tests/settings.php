<?php

declare(strict_types=1);

/* @see \Osm\Framework\Settings\Hints\Settings */
return (object)[
    'locale' => 'lt_LT',
    'db' => [
        'driver' => 'sqlite',
        'database' => ':memory:',
        'prefix' => '',
        'foreign_key_constraints' => true,
    ],
//    'db' => [
//        'driver' => 'mysql',
//        'url' => $_ENV['MYSQL_DATABASE_URL'] ?? null,
//        'host' => $_ENV['MYSQL_HOST'] ?? 'localhost',
//        'port' => $_ENV['MYSQL_PORT'] ?? '3306',
//        'database' => "{$_ENV['MYSQL_DATABASE']}_test",
//        'username' => $_ENV['MYSQL_USERNAME'],
//        'password' => $_ENV['MYSQL_PASSWORD'],
//        'unix_socket' => $_ENV['MYSQL_SOCKET'] ?? '',
//        'charset' => 'utf8mb4',
//        'collation' => 'utf8mb4_unicode_ci',
//        'prefix' => '',
//        'prefix_indexes' => true,
//        'strict' => true,
//        'engine' => null,
//        'options' => extension_loaded('pdo_mysql') ? array_filter([
//            PDO::MYSQL_ATTR_SSL_CA => env('MYSQL_ATTR_SSL_CA'),
//        ]) : [],
//    ],

    'search' => [
        'driver' => 'elastic',
        'index_prefix' => $_ENV['SEARCH_INDEX_PREFIX'],
        'hosts' => [
            $_ENV['ELASTIC_HOST'] ?? 'localhost:9200',
        ],
        'retries' => 2,
        'refresh' => true, // testing-only: index new data immediately
    ],
//    'search' => [
//        'driver' => 'algolia',
//        'index_prefix' => $_ENV['SEARCH_INDEX_PREFIX'],
//        'app_id' => $_ENV['ALGOLIA_APP_ID'],
//        'admin_api_key' => $_ENV['ALGOLIA_ADMIN_API_KEY'],
//        'wait' => true, // testing-only: index new data immediately
//    ],

    /* @see \Osm\Framework\Logs\Hints\LogSettings */
    'logs' => (object)[
        'elastic' => true,
        'db' => true,
    ],
];