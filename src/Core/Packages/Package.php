<?php

namespace Osm\Core\Packages;

use Osm\Core\Object_;

/**
 * @property string $name @required @part
 * @property string $path @required @part
 * @property ComponentPool[] $component_pools @part @required
 * @property string[] $namespaces @part
 * @property bool $project @part
 */
class Package extends Object_
{
}