<?php

declare(strict_types=1);

namespace Osm\Framework\Paths\Traits;

use Osm\Core\App;
use Osm\Core\Attributes\UseIn;
use Osm\Core\Paths;

/**
 * @property string $data
 */
#[UseIn(Paths::class)]
trait PathsTrait
{
    protected function get_data(): string {
        global $osm_app; /* @var App $osm_app */

        /* @var Paths $this */

        return $osm_app::$load_dev_sections
            ? "{$this->project}/sample-data"
            : "{$this->project}/data";
    }
}