<?php

declare(strict_types=1);

namespace Osm\Framework\Search\Filters;

use Osm\Core\Object_;

/**
 * @property string $field_name
 * @property mixed $value
 */
class FieldFilter extends Filter
{
    public static ?string $name;
}