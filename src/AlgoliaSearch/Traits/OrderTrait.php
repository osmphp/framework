<?php

declare(strict_types=1);

namespace Osm\Framework\AlgoliaSearch\Traits;

use Osm\Core\Attributes\Serialized;

/**
 * @property bool $algolia_virtual #[Serialized]
 */
trait OrderTrait
{
    public function algoliaVirtual(): static {
        $this->algolia_virtual = true;

        return $this;
    }
}