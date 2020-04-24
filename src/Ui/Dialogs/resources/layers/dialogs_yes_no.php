<?php

use Osm\Framework\Views\View;
use Osm\Ui\MenuBars\Views\MenuBar;
use Osm\Ui\Menus\Items\Type;

return [
    '@include' => ['modal_dialog'],
    '#dialog' => [
        'modifier' => '-yes-no',
        'items' => [
            'stack_trace' => View::new(['template' => 'Osm_Ui_Dialogs.message']),
        ],
        'footer' => MenuBar::new([
                'modifier' => '-center',
                'items' => [
                    'yes' => [
                        'type' => Type::COMMAND,
                        'title' => osm_t("Yes"),
                    ],
                    'cancel' => [
                        'type' => Type::COMMAND,
                        'title' => osm_t("No"),
                    ],
                ],
        ]),
    ],
];