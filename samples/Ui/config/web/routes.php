<?php

use Manadev\Samples\Ui\Controllers\Web;
use Manadev\Ui\DataTables\Parameters\DataTable;
use Manadev\Framework\Http\Parameters;

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

    'GET /tests/ui/data-tables' => [
        'class' => Web::class,
        'method' => 'dataTablesPage',
        'public' => true,
    ],

    'GET /tests/ui/data-tables/rows' => [
        'class' => Web::class,
        'method' => 'dataTableRows',
        'public' => true,
        'parameters' => [
            '_offset' => ['class' => Parameters\Int_::class, 'required' => true],
            '_limit' => ['class' => Parameters\Int_::class, 'required' => true],
        ],
    ],
];