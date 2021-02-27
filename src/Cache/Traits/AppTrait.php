<?php

declare(strict_types=1);

namespace Osm\Framework\Cache\Traits;

use Osm\Core\App;
use Osm\Framework\Cache\Cache;
use Symfony\Component\Cache\Adapter\TagAwareAdapter;

/**
 * @property TagAwareAdapter $cache
 */
trait AppTrait
{
    /** @noinspection PhpUnused */
    protected function get_cache(): TagAwareAdapter {
        /* @var App $this */
        $new = "{$this->env->CACHE_CLASS_NAME}::new";

        /* @var Cache $factory */
        $factory = $new(['env_prefix' => 'CACHE_']);
        return $factory->create();

    }
}