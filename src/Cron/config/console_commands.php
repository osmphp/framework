<?php

use Osm\Framework\Cron\Commands;

return [
    'cron' => [
        'description' => osm_t("Processes scheduled jobs"),
        'class' => Commands\Process::class,
    ],
];