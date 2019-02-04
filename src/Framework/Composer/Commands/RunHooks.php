<?php

namespace Manadev\Framework\Composer\Commands;

use Manadev\Core\App;
use Manadev\Framework\Composer\Hook;
use Manadev\Framework\Composer\Hooks;
use Manadev\Framework\Composer\Module;
use Manadev\Framework\Console\Command;

/**
 * @property Module $module @required
 * @property Hooks|Hook[] $hooks @required
 */
class RunHooks extends Command
{
    public function default($property) {
        global $m_app; /* @var App $m_app */

        switch ($property) {
            case 'module': return $m_app->modules['Manadev_Framework_Composer'];
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
            $this->output->writeln(m_("No hooks registered for composer event ':event'", ['event' => $event]));
        }
    }
}