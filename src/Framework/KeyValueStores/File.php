<?php

namespace Osm\Framework\KeyValueStores;

use Illuminate\Cache\FileStore;
use Osm\Core\App;

/**
 * @property string $name @required @part
 */
class File extends Store
{
    public function default($property) {
        global $osm_app; /* @var App $osm_app */

        switch ($property) {
            case 'store':
                return new FileStore($osm_app->laravel->files,
                    $osm_app->path("{$osm_app->temp_path}/cache/{$this->name}"));
        }
        return parent::default($property);
    }
}