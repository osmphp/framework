<?php

/** @noinspection PhpUnusedAliasInspection */
declare(strict_types=1);

namespace Osm\Tools\Migrations\Commands;

use Osm\Core\App;
use Osm\Framework\Console\Command;
use Osm\Framework\Console\Attributes\Option;
use Osm\Framework\Console\Attributes\Argument;
use Osm\Runtime\Apps;

/**
 * @property ?string $app #[Option]
 * @property string[] $modules #[Argument]
 * @property ?bool $fresh #[Option]
 */
class Up extends Command
{
    public string $name = 'migrate:up';

    public function run(): void {
        Apps::run(Apps::create($this->app), function(App $app) {
            $migrations = $app->migrations([
                'output' => $this->output,
            ]);

            if ($this->fresh) {
                $migrations->fresh();
            }
            $migrations->up(...$this->modules);
        });
    }
}