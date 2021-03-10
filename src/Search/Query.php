<?php

declare(strict_types=1);

namespace Osm\Framework\Search;

use Osm\Core\Exceptions\NotImplemented;
use Osm\Core\Object_;

/**
 * @property Search $search
 * @property string $index_name
 */
abstract class Query extends Object_
{
    public array $filters = [];

    abstract public function insert(array $data): void;

    public function where(string $fieldName, mixed $value): static {
        $this->filters[$fieldName] = $value;

        return $this;
    }

    abstract public function get(): Result;
    public function value(string $fieldName): mixed {
        throw new NotImplemented();
    }
}