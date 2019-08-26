<?php

namespace Osm\Framework\Data;

use Osm\Core\App;
use Osm\Framework\Cache\CacheItem;
use Osm\Core\Exceptions\NotSupported;
use Osm\Core\Exceptions\NotFound;
use Osm\Core\Object_;

/**
 * @property string $config @required @part
 * @property string $class_ @required @part
 * @property string $not_found_message @part
 * @property array $config_ @part
 */
class ObjectRegistry extends CacheItem
{
    /**
     * @required @part
     * @var Object_[]
     */
    public $items = [];

    protected function default($property) {
        global $osm_app; /* @var App $osm_app */

        switch ($property) {
            case 'config_': return $osm_app->config($this->config);
        }
        return parent::default($property);
    }

    public function offsetExists($name) {
        try {
            return $this->offsetGet($name) !== null;
        }
        catch (\Exception $e) {
            return false;
        }
    }

    public function offsetGet($name) {
        if (!array_key_exists($name, $this->items)) {
            $this->items[$name] = $this->get($name);
        }

        if ($this->not_found_message && !isset($this->items[$name])) {
            throw new NotFound(m_($this->not_found_message, compact('name')));
        }

        return $this->items[$name];
    }

    protected function get($name) {
        global $osm_app; /* @var App $osm_app */

        if (!isset($this->config_[$name])) {
            return null;
        }

        $data = $this->config_[$name];
        unset($this->config_[$name]);
        $this->modified();

        return $osm_app->create($this->class_, $data, $name, $this);
    }

    public function refresh() {
        $this->items = [];
        unset($this->config_);

        if ($this->cache && $this->cache_key) {
            $this->cache->flushTag($this->cache_key);
            $this->cache->forget($this->cache_key);
        }

        $this->modified();
    }
}