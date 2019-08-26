<?php

use Osm\Framework\Testing\Commands;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

return [
    'config:phpunit' => [
        'description' => osm_t('Updates PHPUnit configuration'),
        'class' => Commands\ConfigPhpunit::class,
        'options' => [
            'no-fresh' => [
                'type' => InputOption::VALUE_NONE,
                'description' => osm_t("Skip clearing cache"),
                'shortcut' => 'f',
            ],
            'no-migrate' => [
                'type' => InputOption::VALUE_NONE,
                'description' => osm_t("Skip database migrations of test database"),
                'shortcut' => 'm',
            ],
            'no-webpack' => [
                'type' => InputOption::VALUE_NONE,
                'description' => osm_t("Skip building theme assets in testing environment"),
                'shortcut' => 'w',
            ],
        ],
        'arguments' => [
            'suite' => [
                'type' => InputArgument::IS_ARRAY,
                'description' => osm_t("Test suites to be included. If list is empty, all test suites are included"),
            ],
        ],
    ],
];