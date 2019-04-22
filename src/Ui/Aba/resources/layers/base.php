<?php

use Manadev\Framework\Views\View;

return [
    '#page' => [
        'body_end' => [
            'overlay' => View::new(['template' => 'Manadev_Ui_Aba.overlay']),
        ],
    ],
];