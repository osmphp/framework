<?php

use Osm\Framework\Views\Views\Container;
use Osm\Framework\Views\Views\Page;

return [
    'root' => Page::new([
        'id' => 'page',

        // HTML id of every child view is the same as its alias
        'id_' => '',

        'items' => [
            'main' => Container::new([
                'id' => 'main',
                'css_class' => 'page__main',

                // HTML id of every child view is the same as its alias
                'id_' => '',

                'sort_order' => 100,
                'items' => [
                    'content' => Container::new([
                        'id' => 'content',
                        'css_class' => 'page__content',

                        // HTML id of every child view is the same as its alias
                        'id_' => '',

                        'sort_order' => 10,
                    ]),
                ],
            ]),
        ],
    ]),
];