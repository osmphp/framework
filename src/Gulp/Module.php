<?php

namespace Osm\Framework\Gulp;

use Osm\Core\App;
use Osm\Core\Modules\BaseModule;

/**
 * @property FileWatchers|FileWatcher[] $file_watchers @required
 */
class Module extends BaseModule
{
    public $hard_dependencies = [
        'Osm_Framework_Npm',
    ];

    protected function default($property) {
        global $osm_app;
        /* @var App $osm_app */

        switch ($property) {
            case 'file_watchers': return $osm_app->cache->remember("file_watchers", function ($data) {
                return FileWatchers::new($data);
            });
        }

        return parent::default($property);
    }
}