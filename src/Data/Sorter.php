<?php

namespace Osm\Framework\Data;

class Sorter
{
    public function orderBy(&$items, $propertyName) {
        uasort($items, function($a, $b) use ($propertyName) {
            return $this->compare($a, $b, $propertyName);
        });
    }

    protected function compare($a, $b, $propertyName) {
        if (!is_null($a->$propertyName)) {
            if (!is_null($b->$propertyName)) {
                if ($a->$propertyName < $b->$propertyName) return -1;
                if ($a->$propertyName > $b->$propertyName) return 1;
                return 0;
            }
            else {
                return -1;
            }
        }
        else {
            if (!is_null($b->$propertyName)) {
                return 1;
            }
            else {
                return 0;
            }
        }
    }
}