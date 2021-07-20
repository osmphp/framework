<?php

/** @noinspection PhpUnusedAliasInspection */
declare(strict_types=1);

namespace Osm\Tools\Gulp\Commands;

use Osm\Core\App;
use Osm\Core\BaseModule;
use Osm\Framework\Console\Command;
use Osm\Framework\Console\Attributes\Option;
use Osm\Framework\Themes\Module;
use Osm\Framework\Themes\Theme;
use Osm\Runtime\Apps;

/**
 * @property ?string $app #[Option]
 */
class Config extends Command
{
    public string $name = 'config:gulp';

    public function run(): void {
        Apps::run(Apps::create($this->app), function(App $app) {
            $json = (object)[
                'production' => isset($_ENV['PRODUCTION']),
                'modules' => array_values(array_map(
                    fn(BaseModule $module) => $module->name,
                    $app->modules)),
                'themes' => empty($app->modules[Module::class]->themes)
                    ? []
                    : array_values(array_map(
                        fn(Theme $theme) => $theme->toJson(),
                        $app->modules[Module::class]->themes)),
            ];

            $this->output->writeln(json_encode($json, JSON_PRETTY_PRINT));
        });
    }
}