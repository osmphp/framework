<?php

declare(strict_types=1);

namespace Osm\Framework\AlgoliaSearch;

use Osm\Core\BaseModule;
use Osm\Framework\Search\Field;
use Osm\Framework\Search\Filter;
use Osm\Framework\Search\Order;

class Module extends BaseModule
{
    public static array $requires = [
        \Osm\Framework\Search\Module::class,
    ];
}