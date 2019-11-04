<?php

use Osm\Framework\Sessions\Stores\File;

return [
    'frontend' => [
        'class' => File::class,
        'time_to_live' => 30,
        'cookie_name' => 'SESSION',
        'cookie_path' => '/',
        'cookie_domain' => null,
        'cookie_secure' => false,
        'cookie_http_only' => true,
        'cookie_same_site' => null,
    ],
];