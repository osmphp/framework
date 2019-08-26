<?php

use Osm\Framework\Npm\Commands;

return [
    'config:npm' => [
        'description' => m_("Updates NPM configuration"),
        'class' => Commands\ConfigNpm::class,
    ],
];