<?php

use Osm\Framework\Migrations\Commands;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

return [
    'migrations' => [
        'description' => osm_t("Runs database migrations"),
        'class' => Commands\Migrate::class,
        'options' => [
            'fresh' => [
                'type' => InputOption::VALUE_NONE,
                'description' => osm_t("Drop all tables before running migrations"),
            ],
            'step' => [
                'type' => InputOption::VALUE_IS_ARRAY | InputOption::VALUE_OPTIONAL,
                'description' => osm_t("Steps to be executed. If omitted, --step=schema --step=data are assumed."),
            ],
        ],
        'arguments' => [
            'module' => [
                'type' => InputArgument::IS_ARRAY,
                'description' => osm_t("Modules to migrate"),
            ],
        ],
    ],
    'migrations-back' => [
        'description' => osm_t("Rolls back database migrations"),
        'class' => Commands\MigrateBack::class,
        'options' => [
            'step' => [
                'type' => InputOption::VALUE_IS_ARRAY | InputOption::VALUE_OPTIONAL,
                'description' => osm_t("Steps to be executed. If omitted, --step=schema --step=data are assumed."),
            ],
        ],
        'arguments' => [
            'module' => [
                'type' => InputArgument::IS_ARRAY,
                'description' => osm_t("Modules to migrate"),
            ],
        ],
    ],
];