<?php

declare(strict_types=1);

namespace Osm\Framework\Search;

use Osm\Core\Object_;
use Osm\Framework\Search\Traits\Filterable;

/**
 * @property Query $query
 */
class Where extends Object_
{
    use Filterable;
}