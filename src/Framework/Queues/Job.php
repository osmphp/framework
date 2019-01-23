<?php

namespace Manadev\Framework\Queues;

use Illuminate\Contracts\Queue\ShouldQueue;
use Manadev\Core\Exceptions\NotImplemented;
use Manadev\Core\Object_;

class Job extends Object_ implements ShouldQueue
{
    public function handle() {
        $this->run();
    }

    protected function run() {
        throw new NotImplemented();
    }
}