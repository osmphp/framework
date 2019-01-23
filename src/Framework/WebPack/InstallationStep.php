<?php

namespace Manadev\Framework\WebPack;

use Manadev\Framework\Installer\Step;
use Manadev\Framework\Processes\Process;

class InstallationStep extends Step
{
    public function run() {
        if (env('NO_NPM_WEBPACK')) {
            return;
        }

        Process::runInConsoleExpectingSuccess('npm run webpack', true);
    }
}