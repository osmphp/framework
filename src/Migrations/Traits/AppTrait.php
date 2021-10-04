<?php

declare(strict_types=1);

namespace Osm\Framework\Migrations\Traits;

use Osm\Core\App;
use Osm\Core\Attributes\UseIn;
use Osm\Framework\Migrations\Migrations;

#[UseIn(App::class)]
trait AppTrait
{
    /** @noinspection PhpUnused */
    public function migrations(array $data = []): Migrations {
        return Migrations::new($data);
    }
}