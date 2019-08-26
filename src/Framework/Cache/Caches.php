<?php

namespace Osm\Framework\Cache;

use Osm\Core\App;
use Osm\Core\Exceptions\NotFound;
use Osm\Core\Object_;
use Osm\Core\Profiler;

/**
 * @property Cache[] $items @required @part
 */
class Caches extends Object_
{
    public static function create() {
        global $m_app; /* @var App $m_app */
        global $m_profiler; /* @var Profiler $m_profiler */

        $filename = $m_app->path($m_app->temp_path . '/cache/caches.ser');
        if (file_exists($filename)) {
            if ($m_profiler) $m_profiler->start("caches", 'cache');
            try {
                return unserialize(file_get_contents($filename));
            }
            finally {
                if ($m_profiler) $m_profiler->stop("caches");
            }
        }
        $result = Caches::new();
        file_put_contents(m_make_dir_for($filename), serialize($result));
        @chmod($filename, $m_app->writable_file_permissions);
        return $result;
    }

    public function default($property) {
        switch ($property) {
            case 'items': return $this->get();
        }
        return parent::default($property);
    }

    protected function get() {
        global $m_app; /* @var App $m_app */

        $result = [];

        foreach ($m_app->config('caches') as $name => $data) {
            $result[$name] = Cache::new(array_merge($data, ['parent' => $this]), $name);
        }

        return $result;
    }

    public function offsetExists($name) {
        return array_key_exists($name, $this->items);
    }

    public function offsetGet($name) {
        if (!$this->offsetExists($name)) {
            throw new NotFound(m_("Cache ':name' not found", compact('name')));
        }

        return $this->items[$name];
    }

    public function terminate() {
        global $m_app; /* @var App $m_app */

        foreach ($this->items as $cache) {
            $cache->terminate();
        }

        if ($this->modified) {
            $filename = $m_app->path("{$m_app->temp_path}/cache/caches.ser");
            file_put_contents(m_make_dir_for($filename), serialize($this));
            @chmod($filename, $m_app->writable_file_permissions);

            $this->modified = false;
        }
    }
}