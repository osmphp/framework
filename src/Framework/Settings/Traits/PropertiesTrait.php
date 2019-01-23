<?php

namespace Manadev\Framework\Settings\Traits;

use Manadev\Core\App;
use Manadev\Framework\Settings\Settings;

trait PropertiesTrait
{
    public function Manadev_Core_App__settings(App $app) {
        return $app->cache->remember('settings', function($data) {
            return Settings::new($data);
        });
    }

}