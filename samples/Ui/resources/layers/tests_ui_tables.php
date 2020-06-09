<?php

use Osm\Samples\Ui\Contacts;
use Osm\Ui\Tables\Columns\Column;
use Osm\Ui\Tables\Views\Table;

return [
    '@include' => ['base'],
    '#page' => [
        'modifier' => '-tests-ui-tables',
    ],
    '#content' => [
        'items' => [
            'data_table' => Table::new([
                'id' => 'data_table',
                'sheet' => 't_contacts',
                'main_column' => 'full_name',
                'not_found_message' => osm_t("There are no contacts entered yet."),
                'load_route' => 'GET /tests/ui/tables/rows',
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
    ],
];