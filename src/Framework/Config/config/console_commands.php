<?php

use Osm\Framework\Config\Commands;
use Symfony\Component\Console\Input\InputArgument;

return [
    'show:config' => [
        'description' => osm_t("Shows merged configuration files"),
        'class' => Commands\ShowConfig::class,
        'arguments' => [
            'path' => [
                'type' => InputArgument::OPTIONAL,
                'description' => osm_t("Path to configuration file in module directory"),
            ],
        ],
    ],
];