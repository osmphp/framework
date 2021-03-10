<?php

declare(strict_types=1);

namespace Osm\Framework\Search\Traits;

use Osm\Core\Exceptions\NotImplemented;

trait FieldTrait
{
    public function generateElasticField(): array {
        throw new NotImplemented();
    }
}