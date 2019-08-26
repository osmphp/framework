<?php

namespace Osm\Framework\Queues;

use Osm\Core\Object_;
use Illuminate\Queue\Queue as LaravelQueue;

/**
 * @property LaravelQueue $laravel_queue @required
 */
class Queue extends Object_
{
    const DB = 'db';
}