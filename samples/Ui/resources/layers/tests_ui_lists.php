<?php

use Osm\Ui\Filters\Views\Filters;
use Osm\Ui\Lists\Views\List_;
use Osm\Ui\Menus\Views\CommandItem;
use Osm\Ui\Menus\Views\SubmenuItem;
use Osm\Ui\Pages\Views\Aside;
use Osm\Ui\Pages\Views\Heading;
use Osm\Ui\Filters\Views\DropdownFilter;
use Osm\Ui\Filters\Views\PriceFilter;

return [
    '@include' => ['base'],
    '#page.modifier' => '-tests-ui-lists',
    '#content.items' => [
        'heading' => Heading::new(['id' => 'heading']),
        'filter_aside' => Aside::new([
            'id_' => null,
            'items' => [
                'filters' => Filters::new([
                    'items' => [
                        'group' => DropdownFilter\Checkboxes::new(),
                        'salary' => PriceFilter\Checkboxes::new(),
                    ],
                ]),
            ],
        ]),
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
        'sort_by' => SubmenuItem::new([
            'title' => osm_t("Sort By"),
            'items' => [
                'name_' => CommandItem::new(['title' => osm_t("Name")]),
                'group' => CommandItem::new(['title' => osm_t("Group")]),
                'salary' => CommandItem::new(['title' => osm_t("Salary")]),
            ],
        ]),
        'delete' => CommandItem::new(['title' => osm_t("Delete Selected")]),
    ],
];