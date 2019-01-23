<?php

namespace Manadev\Framework\Queues;

use Manadev\Core\Object_;
use Illuminate\Queue\Queue as LaravelQueue;

/**
 * @property LaravelQueue $laravel_queue @required
 */
class Queue extends Object_
{
    const DB = 'db';
}