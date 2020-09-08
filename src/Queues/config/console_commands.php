<?php

use Osm\Framework\Queues\Commands;
use Symfony\Component\Console\Input\InputArgument;

return [
    'queue' => [
        'description' => osm_t("Processes queued jobs"),
        'class' => Commands\Process::class,
        'arguments' => [
            'queue' => [
                'type' => InputArgument::OPTIONAL,
                'description' => osm_t("Queue to be processed"),
                'default_' => 'default',
            ],
        ],
    ],
];