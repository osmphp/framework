<?php

use Osm\Data\Sheets\Column;
use Osm\Samples\Ui\Contacts;

return [
    't_contacts' => [
        'class' => Contacts::class,
        'columns' => [
            'image' => [
                'type' => Column::FILE,
            ],

        ],
    ],
];