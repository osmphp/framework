<?php

namespace Manadev\Framework\Migrations\Commands;

use Manadev\Framework\Console\Command;
use Manadev\Framework\Migrations\Migrator;

class MigrateBack extends Command
{
    public function run() {
        /* @var Migrator $migrator */
        $migrator = Migrator::new(['output' => $this->output]);

        if ($steps = $this->input->getOption('step')) {
            $migrator->steps = $steps;
        }

        if ($modules = $this->input->getArgument('module')) {
            $migrator->modules = $modules;
        }

        $migrator->migrateBack();
    }
}