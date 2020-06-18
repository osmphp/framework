<?php

use Osm\Framework\Views\Views\Container;
use Osm\Framework\Views\Views\Main;
use Osm\Framework\Views\Views\Page;

return [
    'root' => Page::new([
        'id' => 'page',

        // HTML id of every child view is the same as its alias
        'id_' => '',

        'items' => [
            'main' => Main::new([
                'id' => 'main',
                'modifier' => 'page__main',

                // HTML id of every child view is the same as its alias
                'id_' => '',

                'sort_order' => 100,
                'items' => [
                    'content' => Container::new([
                        'id' => 'content',
                        'modifier' => 'page__content',

                        // HTML id of every child view is the same as its alias
                        'id_' => '',

                        'sort_order' => 10,
                    ]),
                ],
            ]),
        ],
    ]),
];