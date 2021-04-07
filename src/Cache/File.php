<?php

declare(strict_types=1);

namespace Osm\Framework\Cache;

use Osm\Core\App;
use Osm\Framework\Env\Attributes\Env;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;
use Symfony\Component\Cache\Adapter\TagAwareAdapter;

class File extends Cache
{
    #[Env('CACHE_PATH', 'Cache directory absolute path', '{project_path}/temp/cache')]
    protected function get_adapter(): TagAwareAdapter {
        global $osm_app; /* @var App $osm_app */

        $path = $_ENV["{$this->env_prefix}_PATH"] ??
            "{$osm_app->paths->temp}/cache";

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