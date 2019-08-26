<?php

namespace Osm\Framework\Migrations\Commands;

use Osm\Framework\Console\Command;
use Osm\Framework\Migrations\Migrator;

class Migrate extends Command
{
    public function run() {
        /* @var Migrator $migrator */
        $migrator = Migrator::new(['output' => $this->output, 'fresh' => $this->input->getOption('fresh')]);

        if ($steps = $this->input->getOption('step')) {
            $migrator->steps = $steps;
        }

        if ($modules = $this->input->getArgument('module')) {
            $migrator->modules = $modules;
        }

        $migrator->migrate();
    }
}