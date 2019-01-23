<?php

namespace Manadev\Framework\Data;

use Manadev\Core\App;
use Manadev\Framework\Cache\CacheItem;
use Manadev\Core\Exceptions\NotFound;
use Manadev\Core\Exceptions\NotSupported;

/**
 * @property string $config @required @part
 * @property string $class_ @required @part
 * @property string $not_found_message @part
 * @property array $items @required @part
 * @property string $sort_by @part
 * @property Sorter $sorter @required
 * @property array $config_ @required
 */
class CollectionRegistry extends CacheItem implements \IteratorAggregate, \Countable
{
    public function __get($property) {
        global $m_app; /* @var App $m_app */

        switch ($property) {
            case 'config_': return $m_app->config($this->config);
        }

        return parent::__get($property);
    }

    protected function default($property) {
        global $m_app; /* @var App $m_app */

        switch ($property) {
            case 'sorter': return $m_app[Sorter::class];
            case 'items': return $this->get();
        }
        return parent::default($property);
    }

    public function offsetExists($name) {
        return array_key_exists($name, $this->items);
    }

    public function offsetGet($name) {
        if ($this->not_found_message && !$this->offsetExists($name)) {
            throw new NotFound(m_($this->not_found_message, compact('name')));
        }

        return $this->items[$name];
    }

    public function getIterator() {
        return new \ArrayIterator($this->items);
    }

    public function count() {
        return count($this->items);
    }

    protected function get() {
        $result = [];

        foreach ($this->config_ as $name => $data) {
            $result[$name] = $this->createItem($data, $name);
        }

        if ($this->sort_by) {
            $this->sorter->orderBy($result, $this->sort_by);
        }

        $this->modified();

        return $result;
    }

    protected function createItem($data, $name) {
        global $m_app; /* @var App $m_app */

        return $m_app->create($this->class_, $data, $name, $this);
    }

    public function refresh() {
        unset($this->items);
        if ($this->cache && $this->cache_key) {
            $this->cache->flushTag($this->cache_key);
            $this->cache->forget($this->cache_key);
        }
        $this->modified();
    }
}