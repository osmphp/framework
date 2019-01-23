<?php

use Manadev\Framework\Cache;
use Manadev\Framework\KeyValueStores;

return [
    'main' => [
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