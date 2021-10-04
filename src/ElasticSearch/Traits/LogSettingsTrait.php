<?php

declare(strict_types=1);

namespace Osm\Framework\ElasticSearch\Traits;

use Osm\Core\Attributes\UseIn;
use Osm\Framework\Logs\Hints\LogSettings;

/**
 * @property ?bool $elastic
 */
#[UseIn(LogSettings::class)]
trait LogSettingsTrait
{
}