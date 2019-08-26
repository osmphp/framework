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
        global $m_app; /* @var App $m_app */

        switch ($property) {
            case 'files': return $m_app->laravel->files;
            case 'path': return m_make_dir($m_app->path('temp/' . env('APP_ENV') .
                '/sessions/' . $this->area));
            case 'handler': return $m_app->createRaw(FileSessionHandler::class, $this->files,
                $this->path, $this->time_to_live);
        }
        return parent::default($property);
    }
}