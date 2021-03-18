<?php

declare(strict_types=1);

namespace Osm\Framework\Areas\Attributes;

#[\Attribute(\Attribute::TARGET_CLASS | \Attribute::IS_REPEATABLE)]
final class Area
{
    public function __construct(public ?string $class_name,
        public int $sort_order = 0)
    {
    }
}