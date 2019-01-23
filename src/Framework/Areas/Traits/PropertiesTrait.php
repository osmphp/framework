<?php

namespace Manadev\Framework\Areas\Traits;

use Manadev\Core\App;
use Manadev\Framework\Areas\Areas;

trait PropertiesTrait
{
    public function Manadev_Core_App__areas(App $app) {
        return $app->cache->remember('areas', function($data) {
            return Areas::new($data);
        });
    }
    public function Manadev_Core_App__area_(App $app) {
        return $app->areas[$app->area];
    }

}