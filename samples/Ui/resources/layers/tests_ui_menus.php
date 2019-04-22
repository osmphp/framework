<?php

use Manadev\Framework\Views\View;
use Manadev\Framework\Views\Views\Container;
use Manadev\Ui\Buttons\Views\Button;
use Manadev\Ui\MenuBars\Views\MenuBar;
use Manadev\Ui\Menus\Items\Type;
use Manadev\Ui\PopupMenus\Views\PopupMenu;

return [
    '@include' => ['base'],
    '#page' => [
        'modifier' => '-tests-ui-menus',
        'content' => Container::new([
            'id' => 'content',
            'views' => [
                'surface' => MenuBar::new([
                    'items' => [
                        'file' => [
                            'type' => Type::COMMAND,
                            'title' => m_("File"),
                        ],
                        'tests' => [
                            'type' => Type::SUBMENU,
                            'title' => m_("Tests"),
                            'items' => [
                                'tests' => [
                                    'type' => Type::LINK,
                                    'title' => m_("All Tests"),
                                    'url' => m_url('GET /tests/'),
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
                            'title' => m_("File"),
                        ],
                        'tests' => [
                            'type' => Type::SUBMENU,
                            'title' => m_("Tests"),
                            'items' => [
                                'tests' => [
                                    'type' => Type::LINK,
                                    'title' => m_("All Tests"),
                                    'url' => m_url('GET /tests/'),
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
                            'title' => m_("File"),
                        ],
                        'tests' => [
                            'type' => Type::SUBMENU,
                            'title' => m_("Tests"),
                            'items' => [
                                'tests' => [
                                    'type' => Type::LINK,
                                    'title' => m_("All Tests"),
                                    'url' => m_url('GET /tests/'),
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
                            'title' => m_("File"),
                        ],
                        'tests' => [
                            'type' => Type::SUBMENU,
                            'title' => m_("Tests"),
                            'items' => [
                                'tests' => [
                                    'type' => Type::LINK,
                                    'title' => m_("All Tests"),
                                    'url' => m_url('GET /tests/'),
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
                            'title' => m_("File"),
                        ],
                        'tests' => [
                            'type' => Type::SUBMENU,
                            'title' => m_("Tests"),
                            'items' => [
                                'tests' => [
                                    'type' => Type::LINK,
                                    'title' => m_("All Tests"),
                                    'url' => m_url('GET /tests/'),
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
                            'title' => m_("File"),
                        ],
                        'tests' => [
                            'type' => Type::SUBMENU,
                            'title' => m_("Tests"),
                            'items' => [
                                'tests' => [
                                    'type' => Type::LINK,
                                    'title' => m_("All Tests"),
                                    'url' => m_url('GET /tests/'),
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
                            'title' => m_("File"),
                        ],
                        'tests' => [
                            'type' => Type::SUBMENU,
                            'title' => m_("Tests"),
                            'items' => [
                                'tests' => [
                                    'type' => Type::LINK,
                                    'title' => m_("All Tests"),
                                    'url' => m_url('GET /tests/'),
                                ],
                            ],
                        ],
                    ],
                ]),
                'popup_test' => Container::new([
                    'template' => 'Manadev_Samples_Ui.popup_test',
                    'views' => [
                        'button' => Button::new(['title' => m_("Open Popup Menu")]),
                        'menu' => PopupMenu::new([
                            'items' => [
                                [
                                    'type' => Type::LABEL,
                                    'title' => m_("Label"),
                                ],
                                'command' => [
                                    'type' => Type::COMMAND,
                                    'title' => m_("Command"),
                                    'shortcut' => 'Ctrl+B',
                                    'icon' => '-bold',
                                ],
                                'link' => [
                                    'type' => Type::LINK,
                                    'title' => m_("Link"),
                                    'url' => m_url('GET /tests/'),
                                ],
                                'input' => [
                                    'type' => Type::INPUT,
                                    'title' => m_("Input"),
                                ],
                                'submenu' => [
                                    'type' => Type::SUBMENU,
                                    'title' => m_("Submenu"),
                                    'items' => [
                                        'first' => [
                                            'type' => Type::SUBMENU,
                                            'title' => m_("First"),
                                            'items' => [
                                                'some' => [
                                                    'type' => Type::COMMAND,
                                                    'title' => m_("Some"),
                                                ],
                                                'items' => [
                                                    'type' => Type::COMMAND,
                                                    'title' => m_("Items"),
                                                ],
                                            ],
                                        ],
                                        'second' => [
                                            'type' => Type::SUBMENU,
                                            'title' => m_("Second"),
                                            'items' => [
                                                'some' => [
                                                    'type' => Type::COMMAND,
                                                    'title' => m_("Some"),
                                                ],
                                                'items' => [
                                                    'type' => Type::COMMAND,
                                                    'title' => m_("Items"),
                                                ],
                                            ],
                                        ],
                                        'third' => [
                                            'type' => Type::SUBMENU,
                                            'title' => m_("Third"),
                                            'items' => [
                                                'some' => [
                                                    'type' => Type::COMMAND,
                                                    'title' => m_("Some"),
                                                ],
                                                'items' => [
                                                    'type' => Type::COMMAND,
                                                    'title' => m_("Items"),
                                                ],
                                            ],
                                        ],
                                    ],
                                ],
                                ['type' => Type::SEPARATOR],

                                'check1' => [
                                    'type' => Type::COMMAND,
                                    'title' => m_("Checkbox 1"),
                                ],
                                'check2' => [
                                    'type' => Type::COMMAND,
                                    'title' => m_("Checkbox 2"),
                                    'checked' => true,
                                ],
                                'check3' => [
                                    'type' => Type::COMMAND,
                                    'title' => m_("Checkbox 3"),
                                ],
                                ['type' => Type::SEPARATOR],

                                'radio1' => [
                                    'type' => Type::COMMAND,
                                    'title' => m_("Radio button 1"),
                                    'checkbox_group' => 'radios',
                                ],
                                'radio2' => [
                                    'type' => Type::COMMAND,
                                    'title' => m_("Radio button 2"),
                                    'checkbox_group' => 'radios',
                                ],
                                'radio3' => [
                                    'type' => Type::COMMAND,
                                    'title' => m_("Radio button 3"),
                                    'checkbox_group' => 'radios',
                                ],
                            ],
                        ]),
                    ],
                ])
            ],
        ]),
        'footer' => View::new(['template' => 'Manadev_Samples_Ui.footer']),
    ],
];