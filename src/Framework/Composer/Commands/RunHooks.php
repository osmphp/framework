<?php

namespace Osm\Framework\Composer\Commands;

use Osm\Core\App;
use Osm\Framework\Composer\Hook;
use Osm\Framework\Composer\Hooks;
use Osm\Framework\Composer\Module;
use Osm\Framework\Console\Command;

/**
 * @property Module $module @required
 * @property Hooks|Hook[] $hooks @required
 */
class RunHooks extends Command
{
    public function default($property) {
        global $osm_app; /* @var App $osm_app */

        switch ($property) {
            case 'module': return $osm_app->modules['Osm_Framework_Composer'];
            case 'hooks': return $this->module->hooks;
        }

        return parent::default($property);
    }

    public function run() {
        $ran = false;
        $event = $this->input->getArgument('event');

        foreach ($this->hooks as $hook) {
            if (!in_array($event, $hook->events)) {
                continue;
            }

            $hook->output = $this->output;
            $hook->run();
            $ran = true;
        }

        if (!$ran) {
            $this->output->writeln(osm_t("No hooks registered for composer event ':event'", ['event' => $event]));
        }
    }
}