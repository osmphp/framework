<?php

use Manadev\Framework\Installer\Commands;
use Symfony\Component\Console\Input\InputOption;

return [
    'installer' => [
        'description' => m_("Installs the project"),
        'class' => Commands\Install::class,
        'options' => [
            'force' => [
                'shortcut' => 'f',
                'type' => InputOption::VALUE_NONE,
                'description' => m_("Forces installer to run even if project is already installed"),
            ],
        ]
    ],
];