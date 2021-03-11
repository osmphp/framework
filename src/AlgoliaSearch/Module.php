<?php

declare(strict_types=1);

namespace Osm\Framework\AlgoliaSearch;

use Osm\Core\BaseModule;
use Osm\Framework\Search\Fields;
use Osm\Framework\Search\Filters;

class Module extends BaseModule
{
    public static array $traits = [
        Fields\Field::class => Traits\Fields\FieldTrait::class,

        Filters\Filter::class => Traits\Filters\FilterTrait::class,
        Filters\And_::class => Traits\Filters\AndTrait::class,
        Filters\Equals::class => Traits\Filters\EqualsTrait::class,
    ];
}