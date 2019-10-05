<?php

use Osm\Framework\Queues\Commands;
use Symfony\Component\Console\Input\InputOption;

return [
    'queue' => [
        'description' => osm_t("Processes queued jobs"),
        'class' => Commands\Process::class,
        'options' => [
            'queue' => [
                'type' => InputOption::VALUE_REQUIRED,
                'description' => osm_t("Queue to be processed"),
                'default' => 'default',
            ],
        ],
    ],
];