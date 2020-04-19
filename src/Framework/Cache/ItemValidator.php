<?php

namespace Osm\Framework\Cache;

use Osm\Core\Exceptions\NotSupported;
use Osm\Core\Object_;

class ItemValidator extends Object_
{
    public function validateItem($item) {
        if ($item instanceof CacheItem) {
            $this->validateParents($item, $item);
        }
    }

    protected function validateParents(CacheItem $item, Object_ $obj) {
        foreach ($obj->iterateParts() as $part) {
            $this->validateParent($item, $part);
            $this->validateParents($item, $part);
        }
    }

    protected function validateParent(CacheItem $item, Object_ $obj) {
        for ($parent = $obj->parent, $i = 0;
            $i < 100 && $parent && $parent !== $parent->parent;
            $parent = $parent->parent, $i++)
         {
            if ($parent === $item) {
                return;
            }
        }

        throw new NotSupported("Object of class " . get_class($obj) .
            " is not a child of " . get_class($item) .
            ". Check all 'parent' properties in the object hierarchy. ");
    }
}