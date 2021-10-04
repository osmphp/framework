<?php

declare(strict_types=1);

namespace Osm\Framework\ElasticSearch;

use Osm\Core\BaseModule;
use Osm\Framework\Logs\Hints\LogSettings;
use Osm\Framework\Logs\Logs;
use Osm\Framework\Search\Field;
use Osm\Framework\Search\Filter;

class Module extends BaseModule
{
    public static array $requires = [
        \Osm\Framework\Search\Module::class,
        \Osm\Framework\Logs\Module::class,
    ];
}