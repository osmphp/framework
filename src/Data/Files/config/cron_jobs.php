<?php

use Osm\Data\Files\CronJobs\ClearFiles;

return [
    'clear_files' => [
        'class' => ClearFiles::class,
        'schedule' => '* * * * *',//'0 1 * * *',
    ],
];