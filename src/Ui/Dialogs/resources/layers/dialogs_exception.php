<?php

use Osm\Framework\Views\View;
use Osm\Ui\MenuBars\Views\MenuBar;
use Osm\Ui\Menus\Items\Type;

return [
    '@include' => ['modal_dialog'],
    '#dialog' => [
        'modifier' => '-exception',
        'header' => '{{ message }}',
        'views' => [
            'stack_trace' => View::new(['template' => 'Osm_Ui_Dialogs.exception_stack_trace']),
        ],
        'footer' => MenuBar::new([
                'modifier' => '-center',
                'items' => [
                    'cancel' => [
                        'type' => Type::COMMAND,
                        'title' => m_("Close"),
                        'modifier' => '-filled',
                    ],
                ],
        ]),
    ],
];