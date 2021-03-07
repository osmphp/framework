<?php

declare(strict_types=1);

namespace Osm\Framework\Cache\Traits;

use Osm\Core\App;
use Osm\Framework\Cache\Cache;
use Osm\Framework\Cache\File;
use Osm\Framework\Env\Attributes\Env;
use Symfony\Component\Cache\Adapter\TagAwareAdapter;

/**
 * @property TagAwareAdapter $cache
 */
trait AppTrait
{
    /** @noinspection PhpUnused */
    #[Env('CACHE', 'Cache class name', File::class)]
    protected function get_cache(): TagAwareAdapter {
        /* @var App $this */

        $className = $_ENV['CACHE'] ?? File::class;
        $new = "{$className}::new";

        /* @var Cache $factory */
        $factory = $new(['env_prefix' => 'CACHE']);
        return $factory->create();

    }
}