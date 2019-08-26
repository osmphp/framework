<?php

namespace Osm\Framework\Migrations;

use Osm\Framework\Installer\Step;
use Osm\Framework\Processes\Process;

class InstallationStep extends Step
{
    public function run() {
        if (env('DB_NAME')) {
            Process::runInConsoleExpectingSuccess("php run migrations", true);
        }

    }
}