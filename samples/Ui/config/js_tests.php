<?php

return [
    'unit' => [
        'children' => [
            'ui' => ['title' => osm_t("UI"), 'route' => 'GET /tests/unit/ui'],
        ],
    ],
    'ui' => [
        'title' => osm_t("UI Components"),
        'children' => [
            'typography' => ['title' => osm_t("Typography"), 'route' => 'GET /tests/ui/typography'],
            'buttons' => ['title' => osm_t("Buttons"), 'route' => 'GET /tests/ui/buttons'],
            'menus' => ['title' => osm_t("Menus"), 'route' => 'GET /tests/ui/menus'],
            'dialogs' => ['title' => osm_t("Dialogs"), 'route' => 'GET /tests/ui/dialogs'],
            'snack-bars' => ['title' => osm_t("Snack Bars"), 'route' => 'GET /tests/ui/snack-bars'],
            'data-tables' => ['title' => osm_t("Data Tables"), 'route' => 'GET /tests/ui/data-tables'],
        ],
    ],
];