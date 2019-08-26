<?php

use Osm\Core\App;
use Osm\Framework\Views\View;

global $m_app; /* @var App $m_app */;

return [
    '@include' => ['base'],
    '#page' => [
        'title' => m_("Tests"),
        'content' => View::new(['id_' => null, 'template' => 'Osm_Samples_Js.test_list']),
    ],
];