<?php

declare(strict_types=1);

namespace Osm\Framework\Search;

use Osm\Core\Object_;
use Osm\Core\Attributes\Serialized;

/**
 * @property Blueprint $blueprint
 * @property string $name #[Serialized]
 * @property bool $desc #[Serialized]
 * @property Order\By[] $by #[Serialized]
 */
class Order extends Object_
{
    public function by(string $fieldName, bool $desc = false): static {
        $this->by[] = Order\By::new([
            'field_name' => $fieldName,
            'desc' => $desc,
        ]);

        return $this;
    }
}