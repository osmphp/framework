<?php

declare(strict_types=1);

namespace Osm\Framework\Search\Order;

use Osm\Core\Object_;
use Osm\Core\Attributes\Serialized;

/**
 * @property string $field_name #[Serialized]
 * @property bool $desc #[Serialized]
 */
class By extends Object_
{

}