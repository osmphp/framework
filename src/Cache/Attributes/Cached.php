<?php

declare(strict_types=1);

namespace Osm\Framework\Cache\Attributes;

#[\Attribute(\Attribute::TARGET_PROPERTY)]
final class Cached
{
    public function __construct(public string $key,
        public string $cache_name = 'cache',
        public array $tags = [],
        public int|\DateInterval|null $expires_after = null)
    {
    }
}