<?php

use Osm\Framework\Cron\Schedule;
use Osm\Framework\Queues\CronJobs;

return [
    'process_queue' => [
        'class' => CronJobs\ProcessQueue::class,
        'schedule' => '* * * * *',
    ],
];