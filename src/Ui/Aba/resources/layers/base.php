<?php

use Osm\Framework\Views\View;

return [
    '#page' => [
        'body_end' => [
            'overlay' => View::new(['template' => 'Osm_Ui_Aba.overlay']),
        ],
    ],
];