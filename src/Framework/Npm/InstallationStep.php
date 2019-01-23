<?php

namespace Manadev\Framework\Npm;

use Manadev\Framework\Installer\Step;
use Manadev\Framework\Processes\Process;

class InstallationStep extends Step
{
    public function run() {
        if (env('NO_NPM_WEBPACK')) {
            return;
        }

        Process::runInConsoleExpectingSuccess('php run config:npm', true);
        Process::runInConsoleExpectingSuccess('npm install', true);
    }
}