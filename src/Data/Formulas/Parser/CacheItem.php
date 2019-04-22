<?php

namespace Manadev\Data\Formulas\Parser;

use Manadev\Data\Formulas\Formulas\Formula;
use Manadev\Framework\Cache\CacheItem as BaseCacheItem;

/**
 * @property Formula $formula @required @part
 * @property int $parameter_count @required @part
 */
class CacheItem extends BaseCacheItem
{
    public $track_modifications = false;
}