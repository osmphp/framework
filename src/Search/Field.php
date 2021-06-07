<?php

declare(strict_types=1);

namespace Osm\Framework\Search;

use Osm\Core\Object_;
use Osm\Core\Attributes\Serialized;
use Osm\Framework\Search\Field\Sortable;

/**
 * @property Blueprint $blueprint
 * @property string $name #[Serialized]
 * @property string $type #[Serialized]
 * @property bool $array #[Serialized]
 * @property bool $searchable #[Serialized]
 * @property bool $filterable #[Serialized]
 * @property Field\Sortable $sortable #[Serialized]
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

    public function sortable(bool $asc = true, bool $desc = true): static {
        $this->sortable = Sortable::new([
            'asc' => $asc,
            'desc' => $desc,
        ]);

        if ($asc) {
            $this->blueprint->order($this->name, false)
                ->by($this->name, false);
        }

        if ($desc) {
            $this->blueprint->order($this->name, true)
                ->by($this->name, true);
        }

        return $this;
    }

    public function faceted(): static {
        $this->faceted = true;

        return $this;
    }
}