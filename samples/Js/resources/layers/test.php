<?php

use Osm\Framework\Views\View;
use Osm\Samples\Js\Views\TestRunner;

return [
    '@include' => ['base'],
    '#page' => [
        'title' => m_("Test Suite"),
        'modifier' => '-test',
        'head_end' => [
            'testing' => View::new(['template' => 'Osm_Samples_Js.mocha-styles']),
        ],
        'content' => TestRunner::new(['id' => 'test_runner']),
    ],
];