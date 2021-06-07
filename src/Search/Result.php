<?php

declare(strict_types=1);

namespace Osm\Framework\Search;

use Osm\Core\Object_;

/**
 * @property string[]|int[] $ids
 * @property int $count
 * @property Hints\Result\Facet[] $facets
 */
class Result extends Object_
{
    public function facetCount(string $fieldName, mixed $value): ?int {
        if (!isset($this->facets[$fieldName]->counts)) {
            return null;
        }

        foreach ($this->facets[$fieldName]->counts as $count) {
            if ($count->value === $value) {
                return $count->count;
            }
        }

        return null;
    }
}