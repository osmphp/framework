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
        global $m_app; /* @var App $m_app */

        switch ($property) {
            case 'commands':
                return $m_app->cache->remember('console_commands', function() {
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
        global $m_app; /* @var App $m_app */

        try {
            $console = $m_app->laravel->console;

            foreach ($this->commands as $name => $command) {
                $console->add($m_app->createRaw(LaravelCommand::class, $command));
            }
            $m_app->exit_code = $console->run();
        }
        catch (\Throwable $e) {
            $m_app->error_handler->handleException($e);
            $m_app->exit_code = 1;
        }
    }
}