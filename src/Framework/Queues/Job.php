<?php

namespace Osm\Framework\Queues;

use Illuminate\Contracts\Queue\ShouldQueue;
use Osm\Core\Exceptions\NotImplemented;
use Osm\Core\Object_;

class Job extends Object_ implements ShouldQueue
{
    public function handle() {
        $this->run();
    }

    protected function run() {
        throw new NotImplemented();
    }
}