<?php

declare(strict_types=1);

namespace Osm\Framework\AlgoliaSearch\Traits;

use Osm\Core\Attributes\Serialized;
use Osm\Core\Attributes\UseIn;
use Osm\Framework\Search\Order;

/**
 * @property bool $algolia_virtual #[Serialized]
 */
#[UseIn(Order::class)]
trait OrderTrait
{
    public function algoliaVirtual(): static {
        $this->algolia_virtual = true;

        return $this;
    }
}