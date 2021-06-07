<?php

declare(strict_types=1);

namespace Osm\Framework\AlgoliaSearch;

use Osm\Core\BaseModule;
use Osm\Framework\Search\Field;
use Osm\Framework\Search\Filter;

class Module extends BaseModule
{
    public static array $traits = [
        Field::class => Traits\FieldTrait::class,

        Filter::class => Traits\FilterTrait::class,
        Filter\Logical::class => Traits\FilterTrait\Logical::class,
        Filter\Field::class => Traits\FilterTrait\Field::class,
    ];
}