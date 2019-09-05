<?php

use Osm\Framework\Queues;
use Osm\Framework\Queues\Queue;

return [
    'db' => ['class' => Queues\Database::class],
    'redis' => ['class' => Queues\Redis::class],
];