<?php

declare(strict_types=1);

namespace Osm\Framework\Search;

use Osm\Core\Object_;

/**
 * @property Search $search
 * @property string $index_name
 */
abstract class Blueprint extends Object_
{
    abstract public function create(): void;
    abstract public function alter(): void;
    abstract public function drop(): void;
    abstract public function exists(): bool;

    public function int(string $fieldName) {

    }

    public function string(string $fieldName) {

    }
}