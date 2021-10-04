<?php

declare(strict_types=1);

namespace Osm\Framework\Db\Traits;

use Osm\Core\Attributes\UseIn;
use Osm\Framework\Logs\Hints\LogSettings;

/**
 * @property ?bool $db
 */
#[UseIn(LogSettings::class)]
trait LogSettingsTrait
{

}