<?php

use Osm\Framework\Http\Returns;
use Osm\Framework\Profiler\Controllers\Web;
use Osm\Framework\Http\Parameters;

return [
    'GET /profiler/plain-text' => [
        'class' => Web::class,
        'method' => 'plainTextPage',
        'public' => true,
        'returns' => Returns::PLAIN_TEXT,
        'parameters' => [
            'id' => [
                'class' => Parameters\String_::class,
                'required' => true,
            ],
        ],
    ],

];