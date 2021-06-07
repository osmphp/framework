<?php

declare(strict_types=1);

namespace Osm\Framework\AlgoliaSearch\Traits\Filters;

use Osm\Framework\Search\Filter\Field;

trait EqualsTrait
{
    use FilterTrait;

    /** @noinspection PhpUnused */
    public function toAlgoliaQuery(): string {
        /* @var Field $this */
        if (is_string($this->value)) {
            $value = "'{$this->value}'";
        }
        else {
            $value = $this->value;
        }
        return "{$this->field_name}:{$value}";
    }
}