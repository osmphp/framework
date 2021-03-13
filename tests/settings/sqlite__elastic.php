<?php

declare(strict_types=1);

/* @see \Osm\Framework\Settings\Hints\Settings */
return \Osm\merge((object)[
    'db' => [
        'driver' => 'sqlite',
        'database' => ':memory:',
        'prefix' => '',
        'foreign_key_constraints' => true,
    ],
    'search' => [
        'driver' => 'elastic',
        'index_prefix' => $_SERVER['SEARCH_INDEX_PREFIX'],
        'hosts' => [
            $_SERVER['ELASTIC_HOST'] ?? 'localhost:9200',
        ],
        'retries' => 2,
        'refresh' => true, // testing-only: index new data immediately
    ],
//    'search' => [
//        'driver' => 'algolia',
//        'index_prefix' => $_SERVER['SEARCH_INDEX_PREFIX'],
//        'app_id' => $_SERVER['ALGOLIA_APP_ID'],
//        'admin_api_key' => $_SERVER['ALGOLIA_ADMIN_API_KEY'],
//        'wait' => true, // testing-only: index new data immediately
//    ],
], include __DIR__ . '/general.php');