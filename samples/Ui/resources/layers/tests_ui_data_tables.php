<?php

use Osm\Samples\Ui\Contacts;
use Osm\Ui\DataTables\Columns\Column;
use Osm\Ui\DataTables\Views\DataTable;

return [
    '@include' => ['base'],
    '#page' => [
        'modifier' => '-tests-ui-data-tables',
        'content' => DataTable::new([
            'id' => 'data_table',
            'search' => Contacts::class,
            'main_column' => 'full_name',
            'not_found_message' => osm_t("There are no contacts entered yet."),
            'load_route' => 'GET /tests/ui/data-tables/rows',
            'columns' => [
                'full_name' => [
                    'title' => osm_t("Full Name"),
                    'type' => Column::STRING,
                ],
                'phone' => [
                    'title' => osm_t("Phone"),
                    'type' => Column::STRING,
                    'width' => 250.0,
                ],
                'email' => [
                    'title' => osm_t("Email"),
                    'type' => Column::STRING,
                    'width' => 300.0,
                ],
            ],
        ]),
    ],
];