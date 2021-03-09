<?php

declare(strict_types=1);

namespace Osm\Framework\Migrations\Traits;

use Osm\Framework\Migrations\Migrations;

trait AppTrait
{
    /** @noinspection PhpUnused */
    public function migrations(array $data = []): Migrations {
        return Migrations::new($data);
    }
}