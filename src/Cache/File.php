<?php

declare(strict_types=1);

namespace Osm\Framework\Cache;

use Osm\Core\App;
use Osm\Framework\Env\Env;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;
use Symfony\Component\Cache\Adapter\TagAwareAdapter;

/**
 * @property Env $env
 */
class File extends Cache
{
    public function create(): TagAwareAdapter {
        $path = $this->env->{$this->env_prefix . 'PATH'};

        $items = new FilesystemAdapter(directory: $path);
        $tags = new FilesystemAdapter(namespace: 'tags_',
            directory: $path
        );

        return new TagAwareAdapter($items, $tags);
    }

    /** @noinspection PhpUnused */
    protected function get_env(): Env {
        global $osm_app; /* @var App $osm_app */

        return $osm_app->env;
    }
}