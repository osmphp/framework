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
//        'url' => $_ENV['DATABASE_URL'] ?? null,
//        'host' => $_ENV['DB_HOST'] ?? 'localhost',
//        'port' => $_ENV['DB_PORT'] ?? '3306',
//        'database' => "{$_ENV['DB_DATABASE']}_test",
//        'username' => $_ENV['DB_USERNAME'],
//        'password' => $_ENV['DB_PASSWORD'],
//        'unix_socket' => $_ENV['DB_SOCKET'] ?? '',
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
        'index_prefix' => "{$_ENV['SEARCH_INDEX_PREFIX']}_",
        'hosts' => [
            $_ENV['SEARCH_HOST'] ?? 'localhost:9200',
        ],
        'retries' => 2,
    ],

    /* @see \Osm\Framework\Logs\Hints\LogSettings */
    'logs' => (object)[
        'elastic' => true,
        //'db' => true,
    ],
];