<?php

use Osm\Framework\Queues\Commands;

return [
    'queued-jobs' => [
        'description' => osm_t("Processes queued jobs"),
        'class' => Commands\Process::class,
    ],
];