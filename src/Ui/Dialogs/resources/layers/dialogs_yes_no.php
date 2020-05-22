<?php

use Osm\Framework\Views\View;
use Osm\Ui\Menus\Views\CommandItem;
use Osm\Ui\Menus\Views\MenuBar;

return [
    '@include' => ['modal_dialog'],
    '#dialog' => [
        'modifier' => '-yes-no',
        'items' => [
            'stack_trace' => View::new(['template' => 'Osm_Ui_Dialogs.message']),
        ],
        'footer' => MenuBar::new([
                'horizontal_align' => 'center',
                'items' => [
                    'yes' => CommandItem::new([
                        'title' => osm_t("Yes"),
                    ]),
                    'cancel' => CommandItem::new([
                        'title' => osm_t("No"),
                    ]),
                ],
        ]),
    ],
];