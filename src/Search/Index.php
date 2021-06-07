<?php

declare(strict_types=1);

namespace Osm\Framework\Search;

use Osm\Core\Object_;
use Osm\Core\Attributes\Serialized;

/**
 * @property string $name #[Serialized]
 * @property Field[] $fields #[Serialized]
 * @property Order\Order[] $orders #[Serialized]
 */
class Index extends Object_
{

}