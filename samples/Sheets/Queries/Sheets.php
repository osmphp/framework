<?php

declare(strict_types=1);

namespace Osm\Framework\Samples\Sheets\Queries;

use Osm\Core\Attributes\Name;
use Osm\Framework\Data\Query;

#[Name('sheets')]
class Sheets extends Query
{
    public function insert(\stdClass $data): int {
        $id = parent::insert($data);

        return $id;
    }
}