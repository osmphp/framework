<?php

declare(strict_types=1);

namespace Osm\Framework\Cache\Traits;

use Osm\Core\App;
use Osm\Framework\Cache\Cache;
use Osm\Framework\Cache\Descendants;
use Osm\Framework\Cache\File;
use Osm\Framework\Env\Attributes\Env;

/**
 * @property Cache $cache
 * @property Descendants $descendants
 */
trait AppTrait
{
    /** @noinspection PhpUnused */
    #[Env('CACHE', 'Cache class name', File::class)]
    protected function get_cache(): Cache {
        /* @var App $this */

        $className = $_ENV['CACHE'] ?? File::class;
        $new = "{$className}::new";

        /* @var Cache $factory */
        return $new(['env_prefix' => 'CACHE']);
    }

    /** @noinspection PhpUnused */
    protected function get_descendants(): Descendants {
        return Descendants::new(['cache' => $this->cache]);
    }
}