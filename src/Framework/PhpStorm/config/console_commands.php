<?php

use Osm\Framework\PhpStorm\Commands;
use Symfony\Component\Console\Input\InputArgument;

return [
    'config:phpstorm' => [
        'description' => m_("Prepares files for PhpStorm integration"),
        'class' => Commands\ConfigPhpStorm::class,
        'arguments' => [
            'path' => [
                'type' => InputArgument::OPTIONAL,
                'description' => m_("Search path inside resource directory"),
                'default_' => 'js',
            ],
        ],
    ],
];