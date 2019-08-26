<?php

use Osm\Framework\Queues\Processor;
use Osm\Framework\Queues\Queue;

return [
    'queue_store' => Queue::DB,
    'queue_processor' => Processor::CRON,
];