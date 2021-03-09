<?php

declare(strict_types=1);

namespace Osm\Framework\ElasticSearch;

use Osm\Core\BaseModule;

class Module extends BaseModule
{
    public static array $requires = [
        \Osm\Framework\Search\Module::class,
    ];
}