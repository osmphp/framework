<?php

use Manadev\Ui\SnackBars\Controllers\Web;

return [
    'GET /snack-bars/message' => ['class' => Web::class, 'method' => 'getMessageTemplate', 'public' => true],
    'GET /snack-bars/exception' => ['class' => Web::class, 'method' => 'getExceptionTemplate', 'public' => true],
];
