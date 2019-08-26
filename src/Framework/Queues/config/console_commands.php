<?php

use Osm\Framework\Queues\Commands;

return [
    'queued-jobs' => [
        'description' => m_("Processes queued jobs"),
        'class' => Commands\Process::class,
    ],
];