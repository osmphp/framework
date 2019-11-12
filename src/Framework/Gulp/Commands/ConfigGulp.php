<?php

namespace Osm\Framework\Gulp\Commands;

use Osm\Core\App;
use Osm\Framework\Console\Command;

/**
 * `config:gulp` shell command class.
 *
 * @property
 */
class ConfigGulp extends Command
{
    public function default($property) {
        global $osm_app; /* @var App $osm_app */

        switch ($property) {
        }
        return parent::default($property);
    }

    public function run() {
        global $osm_app; /* @var App $osm_app */

        file_put_contents(osm_make_dir_for($osm_app->path("{$osm_app->temp_path}/gulp.json")),
            json_encode($this->getWatchedPatterns(), JSON_PRETTY_PRINT));
    }

    protected function getWatchedPatterns() {
        return ['data/**/*.*'];
    }
}