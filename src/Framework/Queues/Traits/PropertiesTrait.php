<?php

namespace Manadev\Framework\Queues\Traits;

use Manadev\Core\App;
use Manadev\Framework\Queues\Queues;

trait PropertiesTrait
{
    public function Manadev_Core_App__queues(App $app) {
        return $app->cache->remember('queues', function($data) {
            return Queues::new($data);
        });
    }
}