<?php

namespace Osm\Framework\Themes\Attributes;

#[\Attribute(\Attribute::TARGET_CLASS)]
final class Theme
{
    public function __construct(public string $name)
    {
    }
}