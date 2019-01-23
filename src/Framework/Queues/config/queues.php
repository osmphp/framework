<?php

use Manadev\Framework\Queues;
use Manadev\Framework\Queues\Queue;

return [
    Queue::DB => ['class' => Queues\Database::class],
];