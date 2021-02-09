<?php

declare(strict_types=1);

namespace Osm\Framework\Console;

use Osm\Core\App;
use Osm\Core\Module as BaseModule;
use Symfony\Component\Console\Application as SymfonyConsole;

/** @noinspection PhpUnused */

/**
 * @property Command[] $commands
 * @property SymfonyCommand[] $symfony_commands
 * @property SymfonyConsole $symfony
 */
class Module extends BaseModule
{
    public static array $traits = [
        App::class => Traits\AppTrait::class,
    ];

    /** @noinspection PhpUnused */
    protected function get_commands(): array {
        global $osm_app; /* @var App $osm_app */

        $commands = [];

        foreach ($osm_app->classes as $class) {
            if (!is_subclass_of($class->name, Command::class, true)) {
                continue;
            }

            $new = "{$class->name}::new";
            $command = $new();
            $commands[$command->name] = $command;
        }

        return $commands;
    }

    /** @noinspection PhpUnused */
    protected function get_symfony_commands(): array {
        $commands = [];

        foreach ($this->commands as $command) {
            $commands[$command->name] = new SymfonyCommand($command);
        }

        return $commands;
    }

    /** @noinspection PhpUnused */
    protected function get_symfony(): SymfonyConsole {
        $console = new SymfonyConsole();

        foreach ($this->symfony_commands as $command) {
            $console->add($command);
        }

        return $console;
    }
}