<?php

declare(strict_types=1);

namespace Osm\Framework\Cache\Traits;

use Osm\Core\App;
use Osm\Core\Paths;

/**
 * @property string $temp
 */
trait PathsTrait
{
    protected function get_temp(): string {
        global $osm_app; /* @var App $osm_app */

        /* @var Paths $this */
        return "{$this->project}/temp/{$osm_app->name}";
    }
}