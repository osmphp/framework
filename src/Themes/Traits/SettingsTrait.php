<?php

declare(strict_types=1);

namespace Osm\Framework\Themes\Traits;

use Osm\Core\Attributes\UseIn;
use Osm\Framework\Settings\Hints\Settings;

/**
 * @property ?string $theme
 */
#[UseIn(Settings::class)]
trait SettingsTrait
{

}