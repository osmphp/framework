<?php

namespace Osm\Framework\Cache;

use Osm\Core\Object_;

/**
 * @property string $name @required @part
 */
abstract class Cache extends Object_
{
    /**
     * @var CacheItem[]
     */
    public $modified_items = [];

    abstract public function get($key);

    abstract public function put($key, $value, $tags = [], $minutes = null);

    abstract public function forget($key);

    abstract public function flushTag($tag);

    abstract public function flush();

    public function remember($key, callable $callback, $tags = [], $minutes = null) {
        if (($value = $this->get($key)) !== null) {
            /* @var CacheItem $value */
            $value->cache = $this;
            $value->cache_key = $key;
            $value->cache_tags = $tags;
            $value->cache_minutes = $minutes;
            return $value;
        }

        $data = [
            'cache' => $this,
            'cache_key' => $key,
            'cache_tags' => $tags,
            'cache_minutes' => $minutes,
        ];

        $this->put($key, $value = $callback($data), $tags, $minutes);
        return $value;
    }

    public function terminate() {
        foreach (array_keys($this->modified_items) as $key) {
            $this->terminateItem($this->modified_items[$key]);
        }
    }

    public function itemModified(CacheItem $item) {
        $this->modified_items[$item->cache_key] = $item;
    }

    public function terminateItem(CacheItem $item) {
        if (!isset($this->modified_items[$item->cache_key])) {
            return;
        }

        $this->put($item->cache_key, $item, $item->cache_tags ?? [], $item->cache_minutes);

        $item->modified = false;
        unset($this->modified_items[$item->cache_key]);
    }


}