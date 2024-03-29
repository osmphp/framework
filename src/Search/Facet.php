<?php

declare(strict_types=1);

namespace Osm\Framework\Search;

use Osm\Core\Object_;

/**
 * @property Query $query
 * @property string $field_name
 * @property bool $min
 * @property bool $max
 * @property bool $count
 * @property Field $field
 */
class Facet extends Object_
{
    protected function get_field(): Field {
        return $this->query->index->fields[$this->field_name];
    }
}