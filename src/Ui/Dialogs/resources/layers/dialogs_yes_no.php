<?php

use Manadev\Framework\Views\View;
use Manadev\Ui\MenuBars\Views\MenuBar;
use Manadev\Ui\Menus\Items\Type;

return [
    '@include' => ['modal_dialog'],
    '#dialog' => [
        'modifier' => '-yes-no',
        'views' => [
            'stack_trace' => View::new(['template' => 'Manadev_Ui_Dialogs.message']),
        ],
        'footer' => MenuBar::new([
                'modifier' => '-center',
                'items' => [
                    'yes' => [
                        'type' => Type::COMMAND,
                        'title' => m_("Yes"),
                    ],
                    'cancel' => [
                        'type' => Type::COMMAND,
                        'title' => m_("No"),
                    ],
                ],
        ]),
    ],
];