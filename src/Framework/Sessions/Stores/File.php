<?php

namespace Osm\Framework\Sessions\Stores;

use Illuminate\Filesystem\Filesystem;
use Illuminate\Session\FileSessionHandler;
use Osm\Core\App;

/**
 * @property Filesystem $files @required
 * @property string $path @required
 */
class File extends Store
{
    protected function default($property) {
        global $osm_app; /* @var App $osm_app */

        switch ($property) {
            case 'files': return $osm_app->laravel->files;
            case 'path': return m_make_dir($osm_app->path('temp/' . env('APP_ENV') .
                '/sessions/' . $this->area));
            case 'handler': return $osm_app->createRaw(FileSessionHandler::class, $this->files,
                $this->path, $this->time_to_live);
        }
        return parent::default($property);
    }
}