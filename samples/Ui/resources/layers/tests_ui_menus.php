<?php

use Osm\Framework\Views\View;
use Osm\Framework\Views\Views\Container;
use Osm\Ui\Buttons\Views\Button;
use Osm\Ui\MenuBars\Views\MenuBar;
use Osm\Ui\Menus\Items\Type;
use Osm\Ui\PopupMenus\Views\PopupMenu;

return [
    '@include' => ['base'],
    '#page.modifier' => '-tests-ui-menus',
    '#page.items'  => [
        'surface' => MenuBar::new([
            'items' => [
                'file' => [
                    'type' => Type::COMMAND,
                    'title' => osm_t("File"),
                ],
                'tests' => [
                    'type' => Type::SUBMENU,
                    'title' => osm_t("Tests"),
                    'items' => [
                        'tests' => [
                            'type' => Type::LINK,
                            'title' => osm_t("All Tests"),
                            'url' => osm_url('GET /tests/'),
                        ],
                    ],
                ],
            ],
        ]),
        'primary_dark' => MenuBar::new([
            'modifier' => '-primary -dark',
            'items' => [
                'file' => [
                    'type' => Type::COMMAND,
                    'title' => osm_t("File"),
                ],
                'tests' => [
                    'type' => Type::SUBMENU,
                    'title' => osm_t("Tests"),
                    'items' => [
                        'tests' => [
                            'type' => Type::LINK,
                            'title' => osm_t("All Tests"),
                            'url' => osm_url('GET /tests/'),
                        ],
                    ],
                ],
            ],
        ]),
        'primary' => MenuBar::new([
            'modifier' => '-primary',
            'items' => [
                'file' => [
                    'type' => Type::COMMAND,
                    'title' => osm_t("File"),
                ],
                'tests' => [
                    'type' => Type::SUBMENU,
                    'title' => osm_t("Tests"),
                    'items' => [
                        'tests' => [
                            'type' => Type::LINK,
                            'title' => osm_t("All Tests"),
                            'url' => osm_url('GET /tests/'),
                        ],
                    ],
                ],
            ],
        ]),
        'primary_light' => MenuBar::new([
            'modifier' => '-primary -light',
            'items' => [
                'file' => [
                    'type' => Type::COMMAND,
                    'title' => osm_t("File"),
                ],
                'tests' => [
                    'type' => Type::SUBMENU,
                    'title' => osm_t("Tests"),
                    'items' => [
                        'tests' => [
                            'type' => Type::LINK,
                            'title' => osm_t("All Tests"),
                            'url' => osm_url('GET /tests/'),
                        ],
                    ],
                ],
            ],
        ]),
        'secondary_dark' => MenuBar::new([
            'modifier' => '-secondary -dark',
            'items' => [
                'file' => [
                    'type' => Type::COMMAND,
                    'title' => osm_t("File"),
                ],
                'tests' => [
                    'type' => Type::SUBMENU,
                    'title' => osm_t("Tests"),
                    'items' => [
                        'tests' => [
                            'type' => Type::LINK,
                            'title' => osm_t("All Tests"),
                            'url' => osm_url('GET /tests/'),
                        ],
                    ],
                ],
            ],
        ]),
        'secondary' => MenuBar::new([
            'modifier' => '-secondary',
            'items' => [
                'file' => [
                    'type' => Type::COMMAND,
                    'title' => osm_t("File"),
                ],
                'tests' => [
                    'type' => Type::SUBMENU,
                    'title' => osm_t("Tests"),
                    'items' => [
                        'tests' => [
                            'type' => Type::LINK,
                            'title' => osm_t("All Tests"),
                            'url' => osm_url('GET /tests/'),
                        ],
                    ],
                ],
            ],
        ]),
        'secondary_light' => MenuBar::new([
            'modifier' => '-secondary -light',
            'items' => [
                'file' => [
                    'type' => Type::COMMAND,
                    'title' => osm_t("File"),
                ],
                'tests' => [
                    'type' => Type::SUBMENU,
                    'title' => osm_t("Tests"),
                    'items' => [
                        'tests' => [
                            'type' => Type::LINK,
                            'title' => osm_t("All Tests"),
                            'url' => osm_url('GET /tests/'),
                        ],
                    ],
                ],
            ],
        ]),
        'popup_test' => Container::new([
            'template' => 'Osm_Samples_Ui.popup_test',
            'items' => [
                'button' => Button::new(['title' => osm_t("Open Popup Menu")]),
                'menu' => PopupMenu::new([
                    'items' => [
                        [
                            'type' => Type::LABEL,
                            'title' => osm_t("Label"),
                        ],
                        'command' => [
                            'type' => Type::COMMAND,
                            'title' => osm_t("Command"),
                            'shortcut' => 'Ctrl+B',
                            'icon' => '-bold',
                        ],
                        'link' => [
                            'type' => Type::LINK,
                            'title' => osm_t("Link"),
                            'url' => osm_url('GET /tests/'),
                        ],
                        'input' => [
                            'type' => Type::INPUT,
                            'title' => osm_t("Input"),
                        ],
                        'submenu' => [
                            'type' => Type::SUBMENU,
                            'title' => osm_t("Submenu"),
                            'items' => [
                                'first' => [
                                    'type' => Type::SUBMENU,
                                    'title' => osm_t("First"),
                                    'items' => [
                                        'some' => [
                                            'type' => Type::COMMAND,
                                            'title' => osm_t("Some"),
                                        ],
                                        'items' => [
                                            'type' => Type::COMMAND,
                                            'title' => osm_t("Items"),
                                        ],
                                    ],
                                ],
                                'second' => [
                                    'type' => Type::SUBMENU,
                                    'title' => osm_t("Second"),
                                    'items' => [
                                        'some' => [
                                            'type' => Type::COMMAND,
                                            'title' => osm_t("Some"),
                                        ],
                                        'items' => [
                                            'type' => Type::COMMAND,
                                            'title' => osm_t("Items"),
                                        ],
                                    ],
                                ],
                                'third' => [
                                    'type' => Type::SUBMENU,
                                    'title' => osm_t("Third"),
                                    'items' => [
                                        'some' => [
                                            'type' => Type::COMMAND,
                                            'title' => osm_t("Some"),
                                        ],
                                        'items' => [
                                            'type' => Type::COMMAND,
                                            'title' => osm_t("Items"),
                                        ],
                                    ],
                                ],
                            ],
                        ],
                        ['type' => Type::SEPARATOR],

                        'check1' => [
                            'type' => Type::COMMAND,
                            'title' => osm_t("Checkbox 1"),
                        ],
                        'check2' => [
                            'type' => Type::COMMAND,
                            'title' => osm_t("Checkbox 2"),
                            'checked' => true,
                        ],
                        'check3' => [
                            'type' => Type::COMMAND,
                            'title' => osm_t("Checkbox 3"),
                        ],
                        ['type' => Type::SEPARATOR],

                        'radio1' => [
                            'type' => Type::COMMAND,
                            'title' => osm_t("Radio button 1"),
                            'checkbox_group' => 'radios',
                        ],
                        'radio2' => [
                            'type' => Type::COMMAND,
                            'title' => osm_t("Radio button 2"),
                            'checkbox_group' => 'radios',
                        ],
                        'radio3' => [
                            'type' => Type::COMMAND,
                            'title' => osm_t("Radio button 3"),
                            'checkbox_group' => 'radios',
                        ],
                    ],
                ]),
            ],
        ]),
        'footer' => View::new(['template' => 'Osm_Samples_Ui.footer']),
    ],
];