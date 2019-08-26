<?php

use Osm\Framework\Cron\Commands;

return [
    'scheduled-jobs' => [
        'description' => osm_t("Processes scheduled jobs"),
        'class' => Commands\Process::class,
    ],
];