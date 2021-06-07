<?php

/** @noinspection PhpUnusedAliasInspection */
declare(strict_types=1);

namespace Osm\Framework\Search;

use Osm\Core\Object_;
use Osm\Core\Attributes\Serialized;

/**
 * @property string $name #[Serialized]
 * @property string $type #[Serialized]
 * @property bool $array #[Serialized]
 * @property bool $searchable #[Serialized]
 * @property bool $filterable #[Serialized]
 * @property bool $sortable #[Serialized]
 * @property bool $faceted #[Serialized]
 */
class Field extends Object_
{
    public function array(): static {
        $this->array = true;

        return $this;
    }

    public function searchable(): static {
        $this->searchable = true;

        return $this;
    }

    public function filterable(): static {
        $this->filterable = true;

        return $this;
    }

    public function sortable(): static {
        $this->sortable = true;

        return $this;
    }

    public function faceted(): static {
        $this->faceted = true;

        return $this;
    }
}