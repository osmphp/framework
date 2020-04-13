<?php

use Osm\Framework\Views\View;
use Osm\Samples\Js\Views\TestRunner;

return [
    '@include' => ['base'],
    '#page' => [
        'title' => osm_t("Test Suite"),
        'modifier' => '-test',
        'head_end' => [
            'testing' => View::new(['template' => 'Osm_Samples_Js.mocha-styles']),
        ],
    ],
    '#content' => [
        'views' => [
            'test_runner' => TestRunner::new(),
        ],
    ],
];