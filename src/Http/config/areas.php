<?php

use Osm\Framework\Http\Advices\RedirectToBaseUrl;
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
        'advices' => [
            'resolve_base_url' => [
                'class' => RedirectToBaseUrl::class,
                'sort_order' => 10,
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
