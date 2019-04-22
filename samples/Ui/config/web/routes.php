<?php

use Manadev\Samples\Ui\Controllers\Web;
use Manadev\Ui\DataTables\Parameters\DataTable;

return [
    'GET /tests/unit/ui' => ['class' => Web::class, 'method' => 'unitTestPage', 'public' => true],
    'GET /tests/ui/typography' => ['class' => Web::class, 'method' => 'typographyPage', 'public' => true],
    'GET /tests/ui/buttons' => ['class' => Web::class, 'method' => 'buttonPage', 'public' => true],
    'GET /tests/ui/snack-bars' => ['class' => Web::class, 'method' => 'snackBarPage', 'public' => true],
    'GET /snack-bars/test' => ['class' => Web::class, 'method' => 'snackBarTemplate', 'public' => true],

    'GET /tests/ui/menus' => [
        'class' => Web::class,
        'method' => 'menusPage',
        'public' => true,
    ],

    'GET /tests/ui/dialogs' => [
        'class' => Web::class,
        'method' => 'dialogsPage',
        'public' => true,
    ],
];
