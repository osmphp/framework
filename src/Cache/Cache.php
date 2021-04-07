<?php

declare(strict_types=1);

namespace Osm\Framework\Cache;

use Osm\Core\Exceptions\NotImplemented;
use Osm\Core\Object_;
use Symfony\Component\Cache\Adapter\TagAwareAdapter;

/**
 * @property string $env_prefix
 * @property TagAwareAdapter $adapter
 */
class Cache extends Object_
{
    public function get(string $key, callable $callback): mixed {
        return $this->adapter->get($key, $callback);
    }

    public function clear(): void {
        $this->adapter->clear();
    }

    protected function get_adapter(): TagAwareAdapter {
        throw new NotImplemented($this);
    }
}