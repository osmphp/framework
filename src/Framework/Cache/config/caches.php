<?php

use Osm\Framework\Cache;
use Osm\Framework\KeyValueStores;

return [
    'file' => [
        'class' => Cache\CompositeCache::class,
        'store' => [
            'class' => KeyValueStores\File::class,
            'name' => 'main',
        ],
        'tag_store' => [
            'class' => KeyValueStores\File::class,
            'name' => 'main_tags',
        ],
    ],
];