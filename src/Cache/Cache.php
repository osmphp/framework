<?php

declare(strict_types=1);

namespace Osm\Framework\Cache;

use Osm\Core\Object_;
use Symfony\Component\Cache\Adapter\TagAwareAdapter;

/**
 * @property string $env_prefix
 */
abstract class Cache extends Object_
{
    abstract public function create(): TagAwareAdapter;
}