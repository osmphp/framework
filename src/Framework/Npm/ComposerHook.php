<?php

namespace Manadev\Framework\Npm;

use Manadev\Framework\Composer\Hook;
use Manadev\Framework\Processes\Process;

class ComposerHook extends Hook
{
    public $event = 'post-update';

    public function run() {
        if (!Process::runBuffered('npm -v')) {
            return;
        }
        if (env('NO_NPM_WEBPACK')) {
            return;
        }

        Process::runInConsoleExpectingSuccess('php run config:npm', true);
        Process::runInConsoleExpectingSuccess('npm install', true);
    }
}