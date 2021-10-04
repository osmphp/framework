<?php

declare(strict_types=1);

namespace Osm\Framework\Logs\Traits;

use Osm\Core\Attributes\UseIn;
use Osm\Framework\Logs\Hints\LogSettings;
use Osm\Framework\Settings\Hints\Settings;

/**
 * @property LogSettings $logs
 */
#[UseIn(Settings::class)]
trait SettingsTrait
{

}