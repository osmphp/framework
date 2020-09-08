<?php

namespace Osm\Framework\Queues\Traits;

use Osm\Core\App;
use Osm\Framework\Queues\Queues;

trait PropertiesTrait
{
    public function Osm_Core_App__queues(App $app) {
        return $app->cache->remember('queues', function($data) {
            return Queues::new($data);
        });
    }
}