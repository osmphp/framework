<?php

namespace Osm\Framework\Console;

use Osm\Core\App;
use Osm\Core\Modules\BaseModule;

/**
 * @property Commands|Command[] $commands
 */
class Module extends BaseModule
{
    public $short_name = 'console';
    public $hard_dependencies = [
        'Osm_Framework_Laravel',
    ];

    public function default($property) {
        global $osm_app; /* @var App $osm_app */

        switch ($property) {
            case 'commands':
                return $osm_app->cache->remember('console_commands', function() {
                    return Commands::new();
                });
        }
        return parent::default($property);
    }

    public static function detectEnv() {
        global $argv;

        foreach (array_slice($argv, 1) as $arg) {
            if (strpos($arg, '--env=') !== 0) {
                continue;
            }

            return substr($arg, strlen('--env='));
        }

        return null;
    }

    public function run() {
        global $osm_app; /* @var App $osm_app */

        try {
            $console = $osm_app->laravel->console;

            foreach ($this->commands as $name => $command) {
                $console->add($osm_app->createRaw(LaravelCommand::class, $command));
            }
            $osm_app->exit_code = $console->run();
        }
        catch (\Throwable $e) {
            $osm_app->error_handler->handleException($e);
            $osm_app->exit_code = 1;
        }
    }
}