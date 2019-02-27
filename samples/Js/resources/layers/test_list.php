<?php

use Manadev\Core\App;
use Manadev\Framework\Views\View;

global $m_app; /* @var App $m_app */;

return [
    '@include' => ['base'],
    '#page' => [
        'title' => m_("Tests"),
        'content' => View::new(['id_' => null, 'template' => 'Manadev_Samples_Js.test_list']),
    ],
];