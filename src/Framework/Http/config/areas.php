<?php

use Osm\Framework\Http\Parameters;

return [
    'web' => [
        'title' => osm_t("Web"),
        'parameters' => [
            '_env' => [
                'class' => Parameters\String_::class,
                'transient' => true,
                'pattern' => '/development|testing|production/'
            ],
        ],
    ],
    'frontend' => [
        'title' => osm_t("Frontend"),
    ],
    'api' => [
        'title' => osm_t("API"),
        'parameters' => [
            '_env' => [
                'class' => Parameters\String_::class,
                'transient' => true,
                'pattern' => '/development|testing|production/'
            ],
        ],
    ],
];
