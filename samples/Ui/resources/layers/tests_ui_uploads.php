<?php

use Osm\Framework\Views\View;
use Osm\Framework\Views\Views\Container;
use Osm\Ui\Buttons\Views\Button;
use Osm\Ui\Buttons\Views\UploadButton;
use Osm\Ui\Menus\Views\CommandItem;
use Osm\Ui\Menus\Views\PopupMenu;
use Osm\Ui\Menus\Views\UploadCommandItem;
use Osm\Ui\Pages\Views\Heading;

return [
    '@include' => ['base'],
    '#page.modifier' => '-tests-ui-uploads',
    '#page.items' => [
        'footer' => View::new(['template' => 'Osm_Samples_Ui.footer']),
    ],
    '#content.items' => [
        'upload' => UploadButton::new([
            'title' => osm_t("Upload"),
            'accept' => 'image/*',
            'multi_select' => true,
            'route' => 'POST /tests/ui/uploads',
        ]),
        'popup_button' => Button::new([
            'title' => osm_t("Menu"),
        ]),
        'popup_menu' => PopupMenu::new([
            'items' => [
                'upload' => UploadCommandItem::new([
                    'title' => osm_t("Upload"),
                    'accept' => 'image/*',
                    'multi_select' => true,
                    'route' => 'POST /tests/ui/uploads',
                ]),
            ],
        ]),
        'heading' => Heading::new(['id' => 'heading']),
        'uploaded_images' => Container::new([
            'empty' => false,
        ]),
    ],
    '#heading.menu.items' => [
        'upload' => UploadCommandItem::new([
            'title' => osm_t("Upload"),
            'accept' => 'image/*',
            'multi_select' => true,
            'route' => 'POST /tests/ui/uploads',
        ]),
    ],
];