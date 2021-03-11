<?php

declare(strict_types=1);

namespace Osm\Framework\Samples\Data\Traits;

use Osm\Framework\Data\Query;

trait DataTrait
{
    /** @noinspection PhpUnused */
    public function test_products(): Query {
        return Query::new(['sheet_name' => 'test_products']);
    }
}