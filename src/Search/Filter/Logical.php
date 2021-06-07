<?php

declare(strict_types=1);

namespace Osm\Framework\Search\Filter;

use Osm\Framework\Search\Filter;

/**
 * @property string $operator
 */
class Logical extends Filter
{
    /**
     * @var Filter[]
     */
    public array $filters = [];
}