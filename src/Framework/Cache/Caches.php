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
        global $osm_app; /* @var App $osm_app */
        global $osm_profiler; /* @var Profiler $osm_profiler */

        $filename = $osm_app->path($osm_app->temp_path . '/cache/caches.ser');
        if (file_exists($filename)) {
            if ($osm_profiler) $osm_profiler->start("caches", 'cache');
            try {
                return unserialize(file_get_contents($filename));
            }
            finally {
                if ($osm_profiler) $osm_profiler->stop("caches");
            }
        }
        $result = Caches::new();
        file_put_contents(osm_make_dir_for($filename), serialize($result));
        @chmod($filename, $osm_app->writable_file_permissions);
        return $result;
    }

    public function default($property) {
        switch ($property) {
            case 'items': return $this->get();
        }
        return parent::default($property);
    }

    protected function get() {
        global $osm_app; /* @var App $osm_app */

        $result = [];

        foreach ($osm_app->config('caches') as $name => $data) {
            $result[$name] = Cache::new(array_merge($data, ['parent' => $this]), $name);
        }

        return $result;
    }

    public function offsetExists($name) {
        return array_key_exists($name, $this->items);
    }

    public function offsetGet($name) {
        if (!$this->offsetExists($name)) {
            throw new NotFound(osm_t("Cache ':name' not found", compact('name')));
        }

        return $this->items[$name];
    }

    public function terminate() {
        global $osm_app; /* @var App $osm_app */

        foreach ($this->items as $cache) {
            $cache->terminate();
        }

        if ($this->modified) {
            $filename = $osm_app->path("{$osm_app->temp_path}/cache/caches.ser");
            file_put_contents(osm_make_dir_for($filename), serialize($this));
            @chmod($filename, $osm_app->writable_file_permissions);

            $this->modified = false;
        }
    }
}