<?php

namespace Osm\Framework\WebPack\Commands;

use Osm\Core\App;
use Osm\Framework\Areas\Area;
use Osm\Framework\Areas\Areas;
use Osm\Framework\Console\Command;
use Osm\Core\Modules\BaseModule;
use Osm\Framework\Themes\Current;
use Osm\Framework\Themes\Theme;
use Osm\Framework\Themes\Themes;
use Osm\Framework\WebPack\Target;

/**
 * @property BaseModule[] $modules @required
 * @property Themes|Theme[] $themes @required
 * @property Areas|Area[] $areas @required
 * @property Current $current_theme @required
 */
class ConfigWebPack extends Command
{
    /**
     * @param $property
     * @return array|null
     */
    public function default($property) {
        global $osm_app; /* @var App $osm_app */

        switch ($property) {
            case 'modules': return $osm_app->modules;
            case 'themes': return $osm_app->themes;
            case 'areas': return $osm_app->areas;
            case 'current_theme': return $osm_app[Current::class];
        }

        return parent::default($property);
    }

    public function run() {
        global $osm_app; /* @var App $osm_app */

        file_put_contents(osm_make_dir_for($osm_app->path("{$osm_app->temp_path}/webpack.json")),
            json_encode(osm_object($this->getConfig()), JSON_PRETTY_PRINT));
    }


    protected function getConfig() {
        return (object)[
            'modules' => array_values($this->modules),
            'themes' => array_values(array_map(function($theme) {
                if (isset($theme->definitions)) {
                    $theme->definitions = array_values($theme->definitions);
                }
                return $theme;
            }, osm_object($this->themes))),
            'areas' => array_values(osm_object($this->areas)),
            'targets' => $this->getTargets(),
        ];
    }
    protected function getTargets() {
        $result = [];

        foreach ($this->areas as $area) {
            if ($area->abstract) {
                continue;
            }

            if (!$area->resource_path) {
                continue;
            }

            if ($this->input->getOption('all')) {
                foreach ($this->themes as $theme) {
                    $result[] = new Target([
                        'area' => $area->name,
                        'theme' => $theme->name,
                    ]);
                }
            }
            else {
                $result[] = new Target([
                    'area' => $area->name,
                    'theme' => $this->current_theme->get($area->name),
                ]);
            }
        }
        return $result;
    }
}