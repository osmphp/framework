<?php

namespace Osm\Framework\Redis;

use Osm\Framework\Data\CollectionRegistry;

class Connections extends CollectionRegistry
{
    public $config = 'redis';

    protected function get() {
        $this->modified();
        return $this->config_;
    }
}