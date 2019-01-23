<?php

use Manadev\Framework\Cron\Schedule;
use Manadev\Framework\Queues\CronJobs;

return [
    'process_queue' => [
        'class' => CronJobs\ProcessQueue::class,
        'schedule' => Schedule::EVERY_MINUTE,
    ],
];