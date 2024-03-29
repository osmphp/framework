<?php

declare(strict_types=1);

namespace Osm\Framework\Cache;

use Osm\Core\Exceptions\NotImplemented;
use Osm\Core\Object_;
use Symfony\Component\Cache\Adapter\TagAwareAdapter;
use Symfony\Contracts\Cache\ItemInterface;

/**
 * @property string $env_prefix
 * @property TagAwareAdapter $adapter
 */
class Cache extends Object_
{
    public function get(string $key, callable $callback): mixed {
        return $this->adapter->get($key,
            function (ItemInterface $item) use ($callback) {
                $result = $callback($item);

                if ($result instanceof Object_) {
                    $result->__sleep();
                }

                return $result;
            });
    }

    public function clear(): void {
        $this->adapter->clear();
    }

    public function hasItem(string $key): bool {
        return $this->adapter->hasItem($key);
    }

    public function deleteItem(string $key): void {
        $this->adapter->deleteItem($key);
    }

    protected function get_adapter(): TagAwareAdapter {
        throw new NotImplemented($this);
    }
}