<?php

use Osm\Framework\Config\Commands;
use Symfony\Component\Console\Input\InputArgument;

return [
    'show:config' => [
        'description' => m_("Shows merged configuration files"),
        'class' => Commands\ShowConfig::class,
        'arguments' => [
            'path' => [
                'type' => InputArgument::OPTIONAL,
                'description' => m_("Path to configuration file in module directory"),
            ],
        ],
    ],
];