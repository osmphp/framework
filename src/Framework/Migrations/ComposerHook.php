<?php

namespace Manadev\Framework\Migrations;

use Manadev\Framework\Composer\Hook;
use Manadev\Framework\Processes\Process;

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