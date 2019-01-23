<?php
/**
 * @copyright   Copyright (c) http://www.manadev.com
 * @license     http://www.manadev.com/license  Proprietary License
 */

namespace Manadev\Framework\Processes;

use Manadev\Framework\Processes\Exceptions\ProcessFailed;
use Symfony\Component\Process\Process as SymfonyProcess;

class Process
{
    protected static $collectedMessages = false;
    protected static $path = '';

    public static function cd($path, callable $callback) {
        $originalPath = static::$path;
        static::$path = $path;
        try {
            return $callback();
        }
        finally {
            static::$path = $originalPath;
        }
    }

    public static function run($command, callable $callback) {
        $path = static::getBasePath() . (static::$path ? '/' . static::$path : '');
        $process = new SymfonyProcess($command, $path, null, null, null);
        $dir = __DIR__;
        $process->setWorkingDirectory(realpath("$dir/../../../../../.." .
            (static::$path ? '/' . static::$path : '')));
        return $process->run($callback) == 0;
    }

    public static function runInConsole($command, $show = false) {
        if ($show) {
            static::out("> $command\n");
        }

        return static::run($command, function($type, $buffer) {
            static::out($buffer);
        });
    }

    public static function runInConsoleExpectingSuccess($command, $show = false) {
        if (!static::runInConsole($command, $show)) {
            throw new ProcessFailed("Command '$command' failed unexpectedly");
        }
    }

    public static function runBuffered($command) {
        $output = '';
        $result = static::run($command, function($type, $buffer) use (&$output){
            $output .= $buffer;
        });

        return $result ? $output : false;
    }

    public static function start($command, callable $callback) {
        $process = new SymfonyProcess($command, static::getBasePath(), null, null, null);
        $process->start(function($type, $buffer) use ($callback) {
            if (static::$collectedMessages !== false) {
                static::$collectedMessages .= $buffer;
            }
            $callback();
        });
        return $process;
    }

    /**
     * @param SymfonyProcess $process
     * @return bool
     */
    public static function stop($process) {
        return $process->stop(10000) == 0;
    }

    protected static function getBasePath() {
        return dirname(dirname(dirname(dirname(__DIR__))));
    }

    protected static function out($string) {
        echo $string;
    }

    public static function escape($value) {
        return escapeshellcmd($value);
    }
}