<?php

declare(strict_types=1);

namespace Osm\Framework\Http\Traits;

use Osm\Core\Attributes\UseIn;
use Osm\Framework\Settings\Hints\Settings;

/**
 * @property ?string $base_url
 * @property string $title
 * @property string $admin_title
 */
#[UseIn(Settings::class)]
trait SettingsTrait
{

}