<?php

namespace Manadev\Framework\KeyValueStores;

use Illuminate\Cache\FileStore;
use Manadev\Core\App;

/**
 * @property string $name @required @part
 */
class File extends Store
{
    public function default($property) {
        global $m_app; /* @var App $m_app */

        switch ($property) {
            case 'store':
                return new FileStore($m_app->laravel->files,
                    $m_app->path("{$m_app->temp_path}/cache/{$this->name}"));
        }
        return parent::default($property);
    }
}