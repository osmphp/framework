<?php

declare(strict_types=1);

namespace Osm\Framework\Console\Attributes;

/** @noinspection PhpUnused */
#[\Attribute(\Attribute::TARGET_PROPERTY)]
class Argument
{
    public function __construct(public ?string $default = null) {
    }
}