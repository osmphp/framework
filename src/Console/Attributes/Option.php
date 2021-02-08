<?php

declare(strict_types=1);

namespace Osm\Framework\Console\Attributes;

/** @noinspection PhpUnused */
#[\Attribute(\Attribute::TARGET_PROPERTY)]
class Option
{
    public function __construct(public ?string $shortcut = null,
        public ?string $default = null)
    {
    }
}