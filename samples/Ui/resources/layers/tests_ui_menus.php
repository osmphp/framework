<?php

use Osm\Framework\Views\View;
use Osm\Framework\Views\Views\Container;
use Osm\Ui\Buttons\Views\Button;
use Osm\Ui\Menus\Items\Type;
use Osm\Ui\Menus\Views\CommandItem;
use Osm\Ui\Menus\Views\PopupMenu;

return [
    '@include' => ['base'],
    '#page.modifier' => '-tests-ui-menus',
    '#page.items'  => [
        'popup_test' => Container::new([
            'template' => 'Osm_Samples_Ui.popup_test',
            'items' => [
                'button' => Button::new(['title' => osm_t("Open Popup Menu")]),
                'menu' => PopupMenu::new([
                    'items' => [
                        'command' => CommandItem::new([
                            'title' => osm_t("Command"),
                            'shortcut' => 'Ctrl+B',
                            'icon' => '-bold',
                        ]),
                    ],
                ]),
            ],
        ]),
        'footer' => View::new(['template' => 'Osm_Samples_Ui.footer']),
    ],
];