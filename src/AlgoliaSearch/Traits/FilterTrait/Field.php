<?php

declare(strict_types=1);

namespace Osm\Framework\AlgoliaSearch\Traits\FilterTrait;

use Osm\Framework\AlgoliaSearch\Traits\FilterTrait;
use Osm\Framework\Search\Filter;

trait Field
{
    use FilterTrait;

    /** @noinspection PhpUnused */
    public function toAlgoliaQuery(): string {
        /* @var Filter\Field|Field $this */
        if (is_string($this->value)) {
            $value = "'{$this->value}'";
        }
        else {
            $value = $this->value;
        }
        return "{$this->field_name}:{$value}";
    }
}