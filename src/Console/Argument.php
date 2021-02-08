<?php

/** @noinspection PhpUnusedAliasInspection */
declare(strict_types=1);

namespace Osm\Framework\Console;

use Osm\Core\Object_;
use Osm\Core\Attributes\Expected;

/**
 * @property string $name #[Expected]
 * @property int $mode #[Expected]
 * @property ?string $description #[Expected]
 * @property ?string $default #[Expected]
 */
class Argument extends Object_
{

}