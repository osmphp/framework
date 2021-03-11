<?php

declare(strict_types=1);

namespace Osm\Framework\Data\Traits;

use Osm\Framework\Data\Data;

/**
 * @property Data $data
 */
trait AppTrait
{
    protected function get_data(): Data {
        return Data::new();
    }
}