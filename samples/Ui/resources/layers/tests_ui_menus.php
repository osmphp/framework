<?php

use Osm\Framework\Views\View;
use Osm\Framework\Views\Views\Container;
use Osm\Ui\Buttons\Views\Button;
use Osm\Ui\Menus\Views\CheckboxItem;
use Osm\Ui\Menus\Views\CommandItem;
use Osm\Ui\Menus\Views\DelimiterItem;
use Osm\Ui\Menus\Views\LinkItem;
use Osm\Ui\Menus\Views\MenuBar;
use Osm\Ui\Menus\Views\PopupMenu;

return [
    '@include' => ['base'],
    '#page.modifier' => '-tests-ui-menus',
    '#page.items'  => [
        'bar' => MenuBar::new([
            'items' => [
                'bold' => CommandItem::new([
                    'title' => osm_t("Bold"),
                    'shortcut' => 'Ctrl+B',
                    'icon' => '-bold',
                    'main' => true,
                ]),
                'home' => LinkItem::new([
                    'title' => osm_t("Home"),
                    'icon' => '-italic',
                    'url' => osm_url('GET /tests/'),
                    'dangerous' => true,
                ]),
                DelimiterItem::new(),
                'underline' => CheckboxItem::new([
                    'title' => osm_t("Underline"),
                    'checked' => true,
                    'shortcut' => 'Ctrl+U',
                ]),
            ],
        ]),
        'popup_test' => Container::new([
            'template' => 'Osm_Samples_Ui.popup_test',
            'items' => [
                'button' => Button::new(['title' => osm_t("Open Popup Menu")]),
                'menu' => PopupMenu::new([
                    'items' => [
                        'bold' => CommandItem::new([
                            'title' => osm_t("Bold"),
                            'shortcut' => 'Ctrl+B',
                            'icon' => '-bold',
                            'main' => true,
                        ]),
                        'home' => LinkItem::new([
                            'title' => osm_t("Home"),
                            'icon' => '-italic',
                            'url' => osm_url('GET /tests/'),
                            'dangerous' => true,
                        ]),
                        DelimiterItem::new(),
                        'underline' => CheckboxItem::new([
                            'title' => osm_t("Underline"),
                            'checked' => true,
                            'shortcut' => 'Ctrl+U',
                        ]),
                    ],
                ]),
            ],
        ]),
        'footer' => View::new(['template' => 'Osm_Samples_Ui.footer']),
    ],
];