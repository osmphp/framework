<?php

use Manadev\Framework\Composer\Commands;
use Symfony\Component\Console\Input\InputArgument;

return [
    'composer-hooks' => [
        'description' => m_("Runs Composer hooks"),
        'class' => Commands\RunHooks::class,
        'arguments' => [
            'event' => [
                'type' => InputArgument::REQUIRED,
                'description' => m_("Composer event hooks are registered to"),
            ],
        ],
    ],
];