<?php

namespace Osm\Framework\Themes\Traits;

use Osm\Core\App;
use Osm\Framework\Themes\Module;
use Osm\Framework\Themes\Themes;

trait PropertiesTrait
{
    public function Osm_Core_App__theme(App $app) {
        $module = $app->modules['Osm_Framework_Themes']; /* @var Module $module */
        return $module->current->get($app->area);
    }

    public function Osm_Core_App__themes(App $app) {
        return $app->cache->remember('themes', function($data) {
            return Themes::new($data);
        });
    }

    public function Osm_Core_App__theme_(App $app) {
        return $app->themes[$app->theme];
    }
}