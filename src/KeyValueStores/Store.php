<?php

namespace Osm\Framework\KeyValueStores;

use Illuminate\Contracts\Cache\Store as LaravelStore;
use Osm\Core\Object_;

/**
 * @property LaravelStore $store @required
 */
class Store extends Object_ implements LaravelStore
{
    public function get($key) {
        return $this->store->get($key);
    }

    public function many(array $keys) {
        return $this->store->many($keys);
    }

    public function put($key, $value, $minutes) {
        $this->store->put($key, $value, $minutes);
    }

    public function putMany(array $values, $minutes) {
        $this->store->putMany($values, $minutes);
    }

    public function increment($key, $value = 1) {
        return $this->store->increment($key, $value);
    }

    public function decrement($key, $value = 1) {
        return $this->store->decrement($key, $value);
    }

    public function forever($key, $value) {
        $this->store->forever($key, $value);
    }

    public function forget($key) {
        return $this->store->forget($key);
    }

    public function flush() {
        return $this->store->flush();
    }

    public function getPrefix() {
        return $this->store->getPrefix();
    }
}