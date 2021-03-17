<?php

declare(strict_types=1);

/* @see \Osm\Framework\Settings\Hints\Settings */
return \Osm\merge((object)[
    'db' => [
        'driver' => 'sqlite',
        'database' => ':memory:',
        'prefix' => '',
        'foreign_key_constraints' => true,
        'options' => [
            // use the same in-memory database in all tests
            PDO::ATTR_PERSISTENT => true,
        ],
    ],
    'search' => [
        'driver' => 'elastic',
        'index_prefix' => $_ENV['SEARCH_INDEX_PREFIX'],
        'hosts' => [
            $_ENV['ELASTIC_HOST'] ?? 'localhost:9200',
        ],
        'retries' => 2,
        'refresh' => true, // testing-only: index new data immediately
    ],
], include __DIR__ . '/general.php');