<?php

use Manadev\Framework\Queues\Processor;
use Manadev\Framework\Queues\Queue;

return [
    'queue_store' => Queue::DB,
    'queue_processor' => Processor::CRON,
];