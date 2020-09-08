<?php

use Osm\Framework\Queues\Processor;

return [
    'queue_store' => 'db',
    'queue_processor' => Processor::CLI,
];