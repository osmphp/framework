<?php

namespace Manadev\Framework\Themes\Traits;

use Manadev\Core\App;
use Manadev\Framework\Themes\Module;
use Manadev\Framework\Themes\Themes;

trait PropertiesTrait
{
    public function Manadev_Core_App__theme(App $app) {
        $module = $app->modules['Manadev_Framework_Themes']; /* @var Module $module */
        return $module->current->get($app->area);
    }

    public function Manadev_Core_App__themes(App $app) {
        return $app->cache->remember('themes', function($data) {
            return Themes::new($data);
        });
    }

    public function Manadev_Core_App__theme_(App $app) {
        return $app->themes[$app->theme];
    }
}