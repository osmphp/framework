<?php

use Osm\Framework\Npm\Commands;

return [
    'config:npm' => [
        'description' => osm_t("Updates NPM configuration"),
        'class' => Commands\ConfigNpm::class,
    ],
];