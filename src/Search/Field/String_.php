<?php

declare(strict_types=1);

namespace Osm\Framework\Search\Field;

use Osm\Core\Attributes\Name;
use Osm\Framework\Search\Field;

#[Name('string')]
class String_ extends Field
{
    public string $type = 'string';
}