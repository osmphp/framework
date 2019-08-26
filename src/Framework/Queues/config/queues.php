<?php

use Osm\Framework\Queues;
use Osm\Framework\Queues\Queue;

return [
    Queue::DB => ['class' => Queues\Database::class],
];