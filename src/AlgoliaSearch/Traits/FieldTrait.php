<?php

declare(strict_types=1);

namespace Osm\Framework\AlgoliaSearch\Traits;

use Osm\Core\Attributes\UseIn;
use Osm\Framework\Search\Field;

#[UseIn(Field::class)]
trait FieldTrait
{
    public function generateAlgoliaFacet(): string {
        /* @var Field $this */
        return $this->faceted ? $this->name : "filterOnly({$this->name})";
    }
}