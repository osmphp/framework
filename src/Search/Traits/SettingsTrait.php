<?php

declare(strict_types=1);

namespace Osm\Framework\Search\Traits;

use Osm\Core\Attributes\UseIn;
use Osm\Framework\Settings\Hints\Settings;

/**
 * @property array $search
 */
#[UseIn(Settings::class)]
trait SettingsTrait
{

}