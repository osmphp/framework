<?php

use Manadev\Data\Indexing\Commands;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

return [
    'index' => [
        'description' => m_("Indexes calculated data"),
        'class' => Commands\Index::class,
        'options' => [
            'full' => [
                'type' => InputOption::VALUE_NONE,
                'shortcut' => 'f',
                'description' => m_("Index all, not only pending records"),
            ],
            'no-transaction' => [
                'type' => InputOption::VALUE_NONE,
                'shortcut' => 't',
                'description' => m_("Don't start database transactions"),
            ],
        ],
        'arguments' => [
            'target' => [
                'type' => InputArgument::OPTIONAL,
                'description' => m_("Target"),
            ],
            'source' => [
                'type' => InputArgument::OPTIONAL,
                'description' => m_("Source"),
            ],
        ],
    ],
];