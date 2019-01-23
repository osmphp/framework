<?php

namespace Manadev\Framework\WebPack;

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

        Process::runInConsoleExpectingSuccess('npm run webpack', true);
    }
}