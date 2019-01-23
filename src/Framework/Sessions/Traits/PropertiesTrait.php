<?php

namespace Manadev\Framework\Sessions\Traits;

use Manadev\Core\App;
use Manadev\Framework\Sessions\Stores;

trait PropertiesTrait
{
    public function Manadev_Core_App__session_stores(App $app) {
        return $app->cache->remember('session_stores', function($data) {
            return Stores::new($data);
        });
    }
}