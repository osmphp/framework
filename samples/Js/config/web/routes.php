<?php

use Manadev\Framework\Http\Returns;
use Manadev\Samples\Js\Controllers\Web;

return [
    'GET /tests/' => ['class' => Web::class, 'method' => 'testListPage', 'public' => true],
    'GET /tests/api/ajax' => ['class' => Web::class, 'method' => 'ajaxPage', 'public' => true],
    'POST /tests/api/ajax' => ['class' => Web::class, 'method' => 'ajax', 'public' => true, 'returns' => Returns::JSON],
];