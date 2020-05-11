<?php

use Osm\Framework\Views\View;
use Osm\Samples\Ui\Views\Colors;

return [
    '@include' => ['base'],
    '#page.modifier' => '-tests-ui-colors',
    '#content.items' => [
        'colors' => Colors::new(),
    ],
    '#page.items' => [
        'footer' => View::new(['template' => 'Osm_Samples_Ui.footer']),
    ],
];