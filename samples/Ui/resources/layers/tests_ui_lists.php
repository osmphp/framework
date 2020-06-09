<?php

use Osm\Ui\Filters\Views\Filters;
use Osm\Ui\Lists\Views\List_;
use Osm\Ui\Menus\Views\CommandItem;
use Osm\Ui\Menus\Views\SubmenuItem;
use Osm\Ui\Pages\Views\Heading;

return [
    '@include' => ['base'],
    '#page.modifier' => '-tests-ui-lists',
    '#content.items' => [
        'heading' => Heading::new(['id' => 'heading']),
        'list' => List_::new(),
        'filters' => Filters::new(),
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