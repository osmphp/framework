<?php

namespace Manadev\Framework\Migrations;

use Manadev\Framework\Installer\Step;
use Manadev\Framework\Processes\Process;

class InstallationStep extends Step
{
    public function run() {
        if (env('DB_NAME')) {
            Process::runInConsoleExpectingSuccess("php run migrations", true);
        }

    }
}