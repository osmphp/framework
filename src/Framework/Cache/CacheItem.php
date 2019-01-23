<?php

namespace Manadev\Framework\Cache;

use Manadev\Core\App;
use Manadev\Core\Object_;

/**
 * @property Cache $cache
 * @property string $cache_key
 * @property string[] $cache_tags
 * @property int $cache_minutes
 */
class CacheItem extends Object_
{
    public $track_modifications = true;

    public function modified() {
        parent::modified();
        if ($this->cache && $this->track_modifications) {
            $this->cache->itemModified($this);
        }
    }

    public function __destruct() {
        global $m_app; /* @var App $m_app */

        if ($m_app && $this->cache) {
            $this->cache->terminateItem($this);
        }
    }
}