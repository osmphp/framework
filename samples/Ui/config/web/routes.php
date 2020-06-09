<?php

use Osm\Samples\Ui\Controllers\Web;
use Osm\Framework\Http\Parameters;

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

    'GET /tests/ui/tables' => [
        'class' => Web::class,
        'method' => 'tablesPage',
        'public' => true,
    ],

    'GET /tests/ui/tables/rows' => [
        'class' => Web::class,
        'method' => 'tableRows',
        'public' => true,
        'parameters' => [
            '_offset' => ['class' => Parameters\Int_::class, 'required' => true],
            '_limit' => ['class' => Parameters\Int_::class, 'required' => true],
        ],
    ],

    'GET /tests/ui/colors' => [
        'class' => Web::class,
        'method' => 'colorsPage',
        'public' => true,
    ],

    'GET /tests/ui/uploads' => [
        'class' => Web::class,
        'method' => 'uploadsPage',
        'public' => true,
    ],

    'POST /tests/ui/uploads' => [
        'class' => Web::class,
        'method' => 'upload',
        'public' => true,
    ],
];
