<?php

declare(strict_types=1);

namespace Osm\Framework\AlgoliaSearch\Traits;

use Osm\Framework\Search\Field;

trait FieldTrait
{
    public function generateAlgoliaFacet(): string {
        /* @var Field $this */
        return "filterOnly({$this->name})";
    }
}