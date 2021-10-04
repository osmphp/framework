<?php

declare(strict_types=1);

namespace Osm\Framework\Translations\Traits;

use Osm\Core\Attributes\UseIn;
use Osm\Framework\Settings\Hints\Settings;

/**
 * @property string $locale
 */
#[UseIn(Settings::class)]
trait SettingsTrait
{

}