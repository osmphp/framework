<?php

declare(strict_types=1);

namespace Osm\Framework\Browser\Traits;

use Osm\Framework\Browser\Browser;

trait AppTrait
{
    /** @noinspection PhpUnused */
    public function browse(): Browser {
        return Browser::new();
    }
}