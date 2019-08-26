<?php

use Osm\Core\App;
use Osm\Framework\Views\View;

global $osm_app; /* @var App $osm_app */;

return [
    '@include' => ['base'],
    '#page' => [
        'title' => m_("Tests"),
        'content' => View::new(['id_' => null, 'template' => 'Osm_Samples_Js.test_list']),
    ],
];