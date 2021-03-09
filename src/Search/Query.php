<?php

declare(strict_types=1);

namespace Osm\Framework\Search;

use Osm\Core\Object_;

/**
 * @property Search $search
 * @property string $index_name
 */
abstract class Query extends Object_
{
    public function insert(array $data): void {
    }

    public function where(string $fieldName, mixed $value): static {

    }

    public function value(string $fieldName): mixed {

    }
}