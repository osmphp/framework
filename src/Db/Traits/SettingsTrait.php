<?php

declare(strict_types=1);

namespace Osm\Framework\Db\Traits;

use Osm\Core\Attributes\UseIn;
use Osm\Framework\Settings\Hints\Settings;

/**
 * @property array $db
 */
#[UseIn(Settings::class)]
trait SettingsTrait
{

}