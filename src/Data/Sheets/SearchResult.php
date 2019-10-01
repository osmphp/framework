<?php

namespace Osm\Data\Sheets;

use Illuminate\Support\Collection;
use Osm\Core\Object_;

/**
 * @property int $count @required @part
 * @property array $facets @required @part
 * @property Collection|object[] $items @required @part
 */
class SearchResult extends Object_
{

}