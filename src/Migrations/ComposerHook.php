<?php

namespace Osm\Framework\Migrations;

use Osm\Framework\Composer\Hook;
use Osm\Framework\Processes\Process;

class ComposerHook extends Hook
{
    public $events = ['post-update'];

    public function run() {
        if (!env('DB_NAME')) {
            return;
        }

        Process::runInConsoleExpectingSuccess('php run migrations', true);
    }
}