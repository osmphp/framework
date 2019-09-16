<?php

namespace Osm\Framework\Redis;

use Osm\Core\Promise;
use Osm\Framework\Data\CollectionRegistry;

class Connections extends CollectionRegistry
{
    public $config = 'redis';

    protected function get() {
        $this->modified();
        return array_map(function($config) {
            foreach ($config as $key => &$value) {
                if ($value instanceof Promise) {
                    $value = $value->get();
                }
            }

            return $config;
        }, $this->config_);
    }
}