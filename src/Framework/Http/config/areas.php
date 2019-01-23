<?php

use Manadev\Framework\Http\Parameters;

return [
    'web' => [
        'title' => m_("Web"),
        'parameters' => [
            '_env' => [
                'class' => Parameters\String_::class,
                'transient' => true,
                'pattern' => '/development|testing|production/'
            ],
        ],
    ],
    'api' => [
        'title' => m_("API"),
        'parameters' => [
            '_env' => [
                'class' => Parameters\String_::class,
                'transient' => true,
                'pattern' => '/development|testing|production/'
            ],
        ],
    ],
];
