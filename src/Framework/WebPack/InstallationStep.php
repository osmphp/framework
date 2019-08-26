<?php

namespace Osm\Framework\WebPack;

use Osm\Framework\Installer\Step;
use Osm\Framework\Processes\Process;

class InstallationStep extends Step
{
    public function run() {
        if (env('NO_NPM_WEBPACK')) {
            return;
        }

        Process::runInConsoleExpectingSuccess('npm run webpack', true);
    }
}