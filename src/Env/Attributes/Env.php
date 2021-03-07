<?php

declare(strict_types=1);

namespace Osm\Framework\Env\Attributes;

#[\Attribute(\Attribute::TARGET_CLASS | \Attribute::TARGET_METHOD)]
final class Env
{
    public function __construct(public string $variable,
        public ?string $description = null,
        public ?string $default = null)
    {
    }
}