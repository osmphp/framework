<?php

use Osm\Data\Sheets\Column;
use Osm\Samples\Ui\Contacts;

return [
    't_contacts' => [
        'class' => Contacts::class,
        'columns' => [
            'group' => ['type' => Column::OPTION, 'option_list' => 't_contact_groups'],
            'image' => [ 'type' => Column::FILE ],
        ],
    ],
];