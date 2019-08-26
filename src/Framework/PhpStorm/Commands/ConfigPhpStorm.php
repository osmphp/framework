<?php

namespace Osm\Framework\PhpStorm\Commands;

use Osm\Core\App;
use Osm\Framework\Areas\Area;
use Osm\Framework\Areas\Areas;
use Osm\Framework\Console\Command;
use Osm\Core\Modules\BaseModule;

/**
 * @property BaseModule[] $modules @required
 * @property Areas|Area[] $areas @required
 */
class ConfigPhpStorm extends Command
{
    public function default($property) {
        global $osm_app; /* @var App $osm_app */

        switch ($property) {
            case 'modules': return $osm_app->modules;
            case 'areas': return $osm_app->areas;
        }
        return parent::default($property);
    }

    public function run() {
        global $osm_app; /* @var App $osm_app */

        file_put_contents(m_make_dir_for($osm_app->path("{$osm_app->temp_path}/webpack.phpstorm.config.js")),
            $this->generateWebPackConfig());
    }

    protected function generateWebPackConfig() {
        $result = <<<EOT
module.exports = {
    resolve: {
        alias: {        
EOT;
        $result .= "\n";
        $area = $this->areas['web'];

        foreach ($this->modules as $module) {
            $dirs = $this->input->getArgument('path') == 'js'
                ? ['js', 'critical-js']
                : [$this->input->getArgument('path')];

            foreach ($dirs as $dir) {
                $path = "{$module->path}/{$area->resource_path}/{$dir}";
                if (is_dir($path)) {
                    $result .= "            {$module->name}: path.resolve('{$path}/'),\n";
                    break;
                }
            }
        }

        $result .= <<<EOT
        }
    }
};
EOT;

        return $result;
    }
}