<?php

declare(strict_types=1);

namespace Osm\Framework\Search\Filter;

use Osm\Framework\Search\Field as FieldDef;
use Osm\Framework\Search\Filter;

/**
 * @property string $field_name
 * @property string $operator
 * @property mixed $value
 * @property FieldDef $field
 */
class Field extends Filter
{
    protected function get_field(): FieldDef {
        return $this->query->index->fields[$this->field_name];
    }
}