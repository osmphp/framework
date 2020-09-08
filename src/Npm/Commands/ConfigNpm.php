<?php

namespace Osm\Framework\Npm\Commands;

use Osm\Core\App;
use Osm\Framework\Console\Command;
use Osm\Core\Modules\BaseModule;
use Osm\Framework\Themes\Theme;
use Osm\Framework\Themes\Themes;

/**
 * @property BaseModule[] $modules @required
 * @property Themes|Theme[] $themes @required
 */
class ConfigNpm extends Command
{
    /**
     * @temp
     * @var array
     */
    public $data = [];

    public function default($property) {
        global $osm_app; /* @var App $osm_app */

        switch ($property) {
            case 'modules': return $osm_app->modules;
            case 'themes': return $osm_app->themes;
        }
        return parent::default($property);
    }

    public function run() {
        global $osm_app; /* @var App $osm_app */

        foreach ($this->modules as $module) {
            $this->loadModule($module);
        }

        foreach ($this->themes as $theme) {
            $this->loadTheme($theme);
        }
        file_put_contents($osm_app->path( 'package.json'),
            json_encode($this->data, JSON_PRETTY_PRINT));
    }

    protected function loadModule(BaseModule $module) {
        global $osm_app; /* @var App $osm_app */

        $filename = $osm_app->path("{$module->path}/package.json");
        if (!file_exists($filename)) {
            return;
        }

        $this->data = osm_merge($this->data, json_decode(file_get_contents($filename)));
    }

    protected function loadTheme(Theme $theme) {
    }
}