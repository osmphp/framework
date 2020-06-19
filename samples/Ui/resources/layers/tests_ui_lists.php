<?php

use Osm\Framework\Views\Views\Text;
use Osm\Ui\Filters\Views\Filters;
use Osm\Ui\Lists\Views\List_;
use Osm\Ui\Menus\Views\CommandItem;
use Osm\Ui\Menus\Views\SubmenuItem;
use Osm\Ui\Pages\Views\Heading;
use Osm\Ui\Filters\Views\DropdownFilter;
use Osm\Ui\Filters\Views\PriceFilter;

return [
    '@include' => ['base'],
    '#page.modifier' => '-tests-ui-lists',
    //'#main.wrap_modifier' => '-narrow',
    '#sidebar.items' => [
        'filters' => Filters::new([
            'items' => [
                'group' => DropdownFilter\Checkboxes::new(),
                'salary' => PriceFilter\Checkboxes::new(),
            ],
        ]),
    ],
    '#alternative_sidebar.items' => [
        'lorem' => Text::new(['contents' => 'Lorem ipsum ...']),
    ],
    '#content.items' => [
        'heading' => Heading::new(['id' => 'heading']),
        'list' => List_::new([
            'type' => '-grid -contacts',
            'sheet' => 't_contacts',
            'sheet_columns' => [
                'name',
                'image' => [
                    'width' => 160,
                    'height' => 160,
                ],
                'group',
                'salary',
                'phone',
                'email',
            ],
            'item_template' => 'Osm_Samples_Ui.lists.item',
            //'placeholder_template' => 'Osm_Samples_Ui.lists.placeholder',
        ]),
    ],
    '#heading.menu.items' => [
        'new' => CommandItem::new(['title' => osm_t("New")]),
        'filter' => CommandItem::new(['title' => osm_t("Filter")]),
        'sort_by' => SubmenuItem::new([
            'title' => osm_t("Sort"),
            'items' => [
                'name_' => CommandItem::new(['title' => osm_t("By Name")]),
                'group' => CommandItem::new(['title' => osm_t("By Group")]),
                'salary' => CommandItem::new(['title' => osm_t("By Salary")]),
            ],
        ]),
        'delete' => CommandItem::new(['title' => osm_t("Delete Selected")]),
    ],
];