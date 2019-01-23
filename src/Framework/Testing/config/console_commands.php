<?php

use Manadev\Framework\Testing\Commands;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

return [
    'config:phpunit' => [
        'description' => m_('Updates PHPUnit configuration'),
        'class' => Commands\ConfigPhpunit::class,
        'options' => [
            'no-fresh' => [
                'type' => InputOption::VALUE_NONE,
                'description' => m_("Skip clearing cache"),
                'shortcut' => 'f',
            ],
            'no-migrate' => [
                'type' => InputOption::VALUE_NONE,
                'description' => m_("Skip database migrations of test database"),
                'shortcut' => 'm',
            ],
            'no-webpack' => [
                'type' => InputOption::VALUE_NONE,
                'description' => m_("Skip building theme assets in testing environment"),
                'shortcut' => 'w',
            ],
        ],
        'arguments' => [
            'suite' => [
                'type' => InputArgument::IS_ARRAY,
                'description' => m_("Test suites to be included. If list is empty, all test suites are included"),
            ],
        ],
    ],
];