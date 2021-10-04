<?php

declare(strict_types=1);

namespace Osm\Framework\Http\Traits;

use Osm\Core\Attributes\UseIn;
use Osm\Framework\Settings\Hints\Settings;

/**
 * @property ?string $base_url
 */
#[UseIn(Settings::class)]
trait SettingsTrait
{

}