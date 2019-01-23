<?php

namespace Manadev\Framework\WebPack\Commands;

use Manadev\Core\App;
use Manadev\Framework\Areas\Area;
use Manadev\Framework\Areas\Areas;
use Manadev\Framework\Console\Command;
use Manadev\Core\Modules\BaseModule;
use Manadev\Framework\Themes\Current;
use Manadev\Framework\Themes\Theme;
use Manadev\Framework\Themes\Themes;
use Manadev\Framework\WebPack\Target;

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
        global $m_app; /* @var App $m_app */

        switch ($property) {
            case 'modules': return $m_app->modules;
            case 'themes': return $m_app->themes;
            case 'areas': return $m_app->areas;
            case 'current_theme': return $m_app[Current::class];
        }

        return parent::default($property);
    }

    public function run() {
        global $m_app; /* @var App $m_app */

        file_put_contents(m_make_dir_for($m_app->path("{$m_app->temp_path}/webpack.json")),
            json_encode(m_object((object)[
                'modules' => array_values($this->modules),
                'themes' => array_values(array_map(function($theme) {
                    if (isset($theme->definitions)) {
                        $theme->definitions = array_values($theme->definitions);
                    }
                    return $theme;
                }, m_object($this->themes))),
                'areas' => array_values(m_object($this->areas)),
                'targets' => $this->getTargets()
            ]), JSON_PRETTY_PRINT));
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