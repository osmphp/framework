<?php

namespace Osm\Data\Formulas\Parser;

use Osm\Data\Formulas\Formulas\Formula;
use Osm\Framework\Cache\CacheItem as BaseCacheItem;

/**
 * @property Formula $formula @required @part
 * @property int $parameter_count @required @part
 */
class CacheItem extends BaseCacheItem
{
    public $track_modifications = false;
}