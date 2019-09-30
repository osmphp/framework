<?php

use Osm\Data\Indexing\Commands;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

return [
    'index' => [
        'description' => osm_t("Indexes calculated data"),
        'class' => Commands\Index::class,
        'options' => [
            'full' => [
                'type' => InputOption::VALUE_NONE,
                'shortcut' => 'f',
                'description' => osm_t("Index all, not only pending records"),
            ],
            'no-transaction' => [
                'type' => InputOption::VALUE_NONE,
                'shortcut' => 't',
                'description' => osm_t("Don't start database transactions"),
            ],
            'group' => [
                'type' => InputOption::VALUE_OPTIONAL,
                'description' => osm_t("Target group"),
            ],
        ],
        'arguments' => [
            'target' => [
                'type' => InputArgument::OPTIONAL,
                'description' => osm_t("Target"),
            ],
            'source' => [
                'type' => InputArgument::OPTIONAL,
                'description' => osm_t("Source"),
            ],
        ],
    ],
];