<?php

use Osm\Ui\SnackBars\Views\Panel;

return [
    '#page' => [
        'body_end' => [
            'snack-bar-panel' => Panel::new(),
        ],
    ],
];