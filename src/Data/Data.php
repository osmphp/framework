<?php

declare(strict_types=1);

namespace Osm\Framework\Data;

use Osm\Core\Exceptions\NotImplemented;
use Osm\Core\Object_;

class Data extends Object_
{
    public function create(string $sheetName, callable $callback): void {
        $callback($blueprint = $this->createBlueprint($sheetName));
        throw new NotImplemented();
    }

    public function alter(string $sheetName, callable $callback): void {
        $callback($blueprint = $this->createBlueprint($sheetName));
        throw new NotImplemented();
    }

    public function drop(string $sheetName): void {
        throw new NotImplemented();
    }

    public function exists(string $sheetName): bool {
        throw new NotImplemented();
    }

    protected function createBlueprint(string $sheetName): Blueprint {
        return Blueprint::new([
            'sheet_name' => $sheetName,
        ]);
    }

}