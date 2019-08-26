<?php

namespace Osm\Core\Config;

use Osm\Core\App;
use Osm\Core\Object_;

/**
 * @property string $name @required @part
 */
class ConfigLoader extends Object_
{
    public function load() {
        global $osm_app; /* @var App $osm_app */

        $result = [];

        foreach ($osm_app->modules as $module) {
            $filename = $osm_app->path("{$module->path}/config/{$this->name}.php");
            if (file_exists($filename)) {
                $result = m_merge($result, $this->loadFile($filename));
            }
        }

        $filename = $osm_app->path("{$osm_app->config_path}/{$this->name}.php");
        if (file_exists($filename)) {
            $result = m_merge($result, $this->loadFile($filename));
        }

        return $result;
    }

    protected function loadFile($__filename) {
        /** @noinspection PhpIncludeInspection */
        return include $__filename;
    }
}