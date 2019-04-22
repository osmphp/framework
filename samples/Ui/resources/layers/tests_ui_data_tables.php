<?php

use Manadev\Samples\Ui\Contacts;
use Manadev\Ui\DataTables\Columns\Column;
use Manadev\Ui\DataTables\Views\DataTable;

return [
    '@include' => ['base'],
    '#page' => [
        'modifier' => '-tests-ui-data-tables',
        'content' => DataTable::new([
            'id' => 'data_table',
            'search' => Contacts::class,
            'main_column' => 'full_name',
            'not_found_message' => m_("There are no contacts entered yet."),
            'load_route' => 'GET /tests/ui/data-tables/rows',
            'columns' => [
                'full_name' => [
                    'title' => m_("Full Name"),
                    'type' => Column::STRING,
                ],
                'phone' => [
                    'title' => m_("Phone"),
                    'type' => Column::STRING,
                    'width' => 250.0,
                ],
                'email' => [
                    'title' => m_("Email"),
                    'type' => Column::STRING,
                    'width' => 300.0,
                ],
            ],
        ]),
    ],
];