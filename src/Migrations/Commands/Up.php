<?php

declare(strict_types=1);

namespace Osm\Framework\Migrations\Commands;

use Osm\Core\App;
use Osm\Framework\Console\Command;
use Osm\Framework\Console\Attributes\Option;
use Osm\Framework\Console\Attributes\Argument;

/**
 * @property string[] $modules #[Argument]
 * @property ?bool $fresh #[Option]
 */
class Up extends Command
{
    public string $name = 'migrate:up';

    public function run(): void {
        global $osm_app; /* @var App $osm_app */

        $migrations = $osm_app->migrations(['output' => $this->output]);

        if ($this->fresh) {
            $migrations->fresh();
        }
        $migrations->up(...$this->modules);
    }
}