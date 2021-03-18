<?php

declare(strict_types=1);

namespace Osm\Framework\Http\Traits\Areas;

use Osm\Framework\Http\Request;

trait AreaTrait
{
    public function detect(Request $request): bool {
        return false;
    }
}