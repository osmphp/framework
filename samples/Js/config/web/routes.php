<?php

use Manadev\Framework\Http\Returns;
use Manadev\Samples\Js\Controllers\Web;

return [
    'GET /tests/' => ['class' => Web::class, 'method' => 'testListPage', 'public' => true],
    'GET /tests/unit/framework' => ['class' => Web::class, 'method' => 'unitTestPage', 'public' => true],
    'POST /tests/framework/ajax' => [
        'class' => Web::class,
        'method' => 'ajax',
        'public' => true,
        'returns' => Returns::JSON
    ],
    'POST /tests/framework/not-implemented' => [
        'class' => Web::class,
        'method' => 'notImplemented',
        'public' => true,
    ],
    'POST /tests/framework/error' => [
        'class' => Web::class,
        'method' => 'error',
        'public' => true,
        'returns' => Returns::JSON
    ],
];