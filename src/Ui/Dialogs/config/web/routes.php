<?php

use Manadev\Ui\Dialogs\Controllers\Web;

return [
    'GET /dialogs/exception' => ['class' => Web::class, 'method' => 'exceptionDialog', 'public' => true],
    'GET /dialogs/yes-no' => ['class' => Web::class, 'method' => 'yesNoDialog', 'public' => true],
];
