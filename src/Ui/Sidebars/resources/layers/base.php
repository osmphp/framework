<?php

use Osm\Ui\Sidebars\Views\Sidebar;

return [
    '#main.items' => [
        'sidebar' => Sidebar::new([
            'id' => 'sidebar',
            'css_class' => 'page__sidebar',
            'sort_order' => 20,
        ]),
        'alternative_sidebar' => Sidebar::new([
            'id' => 'alternative_sidebar',
            'css_class' => 'page__alternative-sidebar',
            'sort_order' => 30,
        ]),

    ],
];