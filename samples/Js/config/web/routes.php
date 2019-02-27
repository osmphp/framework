<?php

use Manadev\Samples\Js\Controllers\Web;

return [
    'GET /tests/' => ['class' => Web::class, 'method' => 'testListPage', 'public' => true],
    'GET /tests/api/ajax' => ['class' => Web::class, 'method' => 'ajaxPage', 'public' => true],
];