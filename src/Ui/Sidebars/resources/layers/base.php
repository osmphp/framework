<?php

use Osm\Core\Promise;
use Osm\Ui\Sidebars\Views\Sidebar;

return [
    '#main.items' => [
        'sidebar' => Sidebar::new([
            'id' => 'sidebar',
            'modifier' => 'page__sidebar',
            'sort_order' => 20,
        ]),
        'alternative_sidebar' => Sidebar::new([
            'id' => 'alternative_sidebar',
            'modifier' => 'page__alternative-sidebar',
            'sort_order' => 30,
        ]),
    ],
];