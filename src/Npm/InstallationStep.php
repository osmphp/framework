<?php

namespace Osm\Framework\Npm;

use Osm\Framework\Installer\Step;
use Osm\Framework\Processes\Process;

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