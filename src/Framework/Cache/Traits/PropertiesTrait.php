<?php

namespace Manadev\Framework\Cache\Traits;

use Manadev\Core\App;
use Manadev\Framework\Cache\Caches;

trait PropertiesTrait
{
    public function Manadev_Core_App__caches(App $app) {
        return Caches::create();
    }

    public function Manadev_Core_App__cache(App $app) {
        return $app->caches['main'];
    }
}