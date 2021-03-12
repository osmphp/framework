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
        'driver' => 'algolia',
        'index_prefix' => $_ENV['SEARCH_INDEX_PREFIX'],
        'app_id' => $_ENV['ALGOLIA_APP_ID'],
        'admin_api_key' => $_ENV['ALGOLIA_ADMIN_API_KEY'],
        'wait' => true, // testing-only: index new data immediately
    ],
], include __DIR__ . '/general.php');