<?php

declare(strict_types=1);

namespace Osm\Framework\AlgoliaSearch;

use Osm\Core\BaseModule;
use Osm\Framework\Search\Field;
use Osm\Framework\Search\Filter;

class Module extends BaseModule
{
    public static array $traits = [
        Field::class => Traits\Fields\FieldTrait::class,

        Filter::class => Traits\Filters\FilterTrait::class,
        Filter\And_::class => Traits\Filters\AndTrait::class,
    ];
}