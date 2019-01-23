<?php

use Manadev\Framework\Http\Returns;
use Manadev\Framework\Profiler\Controllers\Web;
use Manadev\Framework\Http\Parameters;

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