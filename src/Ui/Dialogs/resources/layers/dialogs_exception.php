<?php

use Osm\Framework\Views\View;
use Osm\Ui\Menus\Views\CommandItem;
use Osm\Ui\Menus\Views\MenuBar;

return [
    '@include' => ['modal_dialog'],
    '#dialog' => [
        'modifier' => '-exception',
        'header' => '{{ message }}',
        'items' => [
            'stack_trace' => View::new(['template' => 'Osm_Ui_Dialogs.exception_stack_trace']),
        ],
        'footer' => MenuBar::new([
            'horizontal_align' => 'center',
            'items' => [
                'cancel' => CommandItem::new([
                    'title' => osm_t("Close"),
                    'main' => 'true',
                ]),
            ],
        ]),
    ],
];