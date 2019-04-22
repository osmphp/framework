<?php

use Manadev\Framework\Views\View;
use Manadev\Ui\MenuBars\Views\MenuBar;
use Manadev\Ui\Menus\Items\Type;

return [
    '@include' => ['modal_dialog'],
    '#dialog' => [
        'modifier' => '-exception',
        'header' => '{{ message }}',
        'views' => [
            'stack_trace' => View::new(['template' => 'Manadev_Ui_Dialogs.exception_stack_trace']),
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