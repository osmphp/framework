<?php

use Osm\Framework\Installer\Commands;
use Symfony\Component\Console\Input\InputOption;

return [
    'installer' => [
        'description' => osm_t("Installs the project"),
        'class' => Commands\Install::class,
        'options' => [
            'force' => [
                'shortcut' => 'f',
                'type' => InputOption::VALUE_NONE,
                'description' => osm_t("Forces installer to run even if project is already installed"),
            ],
        ]
    ],
];