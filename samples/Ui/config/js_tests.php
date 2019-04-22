<?php

return [
    'unit' => [
        'children' => [
            'ui' => ['title' => m_("UI"), 'route' => 'GET /tests/unit/ui'],
        ],
    ],
    'ui' => [
        'title' => m_("UI Components"),
        'children' => [
            'typography' => ['title' => m_("Typography"), 'route' => 'GET /tests/ui/typography'],
            'buttons' => ['title' => m_("Buttons"), 'route' => 'GET /tests/ui/buttons'],
            'menus' => ['title' => m_("Menus"), 'route' => 'GET /tests/ui/menus'],
            'dialogs' => ['title' => m_("Dialogs"), 'route' => 'GET /tests/ui/dialogs'],
            'snack-bars' => ['title' => m_("Snack Bars"), 'route' => 'GET /tests/ui/snack-bars'],
        ],
    ],
];