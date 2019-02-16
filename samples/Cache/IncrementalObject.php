<?php

namespace Manadev\Samples\Cache;

use Manadev\Framework\Cache\CacheItem;

/**
 * @property string $incremental_property @part
 */
class IncrementalObject extends CacheItem
{
    public function default($property) {
        switch ($property) {
            case 'incremental_property':
                return 5;
        }
        return parent::default($property);
    }
}