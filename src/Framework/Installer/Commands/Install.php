<?php

namespace Manadev\Framework\Installer\Commands;

use Manadev\Core\App;
use Manadev\Framework\Console\Command;
use Manadev\Framework\Installer\Module;
use Manadev\Framework\Installer\Question;
use Manadev\Framework\Installer\Questions;
use Manadev\Framework\Installer\Requirement;
use Manadev\Framework\Installer\Requirements;
use Manadev\Framework\Installer\Step;
use Manadev\Framework\Installer\Steps;
use Manadev\Framework\Processes\Process;

/**
 * @property string $flag @required
 * @property Module $module @required
 * @property Questions|Question[] $questions @required
 * @property Steps|Step[] $steps @required
 * @property Requirements|Requirement[] $requirements @required
 * @property string $yes @required
 * @property string $no @required
 */
class Install extends Command
{
    public function default($property) {
        global $m_app; /* @var App $m_app */

        switch ($property) {
            case 'flag': return $m_app->path("{$m_app->temp_path}/installed.flag");
            case 'module': return $m_app->modules['Manadev_Framework_Installer'];
            case 'questions': return $this->module->questions;
            case 'steps': return $this->module->steps;
            case 'requirements': return $this->module->requirements;
            case 'yes': return (string)m_("Yes");
            case 'no': return (string)m_("No");
        }
        return parent::default($property);
    }

    public function run() {
        global $m_app; /* @var App $m_app */

        if (file_exists($this->flag)) {
            if (!$this->input->getOption('force')) {
                $this->output->writeln(m_("Project is already installed."));

                return 0;
            }
            unlink($this->flag);
        }

        if ($this->input->isInteractive()) {
            $this->output->writeln("");
            $this->output->writeln((string)m_("Welcome! This program will perform all necessary installation steps. But first, please answer some questions:"));
            $this->output->writeln("");

            foreach ($this->requirements as $requirement) {
                $requirement->output = $this->output;
                if (!$requirement->check()) {
                    return 1;
                }
            }

            foreach ($this->questions as $question) {
                $question->output = $this->output;
                $question->ask();
            }

            $this->output->writeln("");

            if ($this->yes != $this->output->choice(m_("That's all the questions. Start installation?"),
                [$this->no, $this->yes], $this->yes))
            {
                return 1;
            }

        }

        Process::runInConsoleExpectingSuccess("php fresh", true);

        foreach ($this->steps as $step) {
            $step->output = $this->output;
            $step->run();
        }

        file_put_contents(m_make_dir_for($this->flag, $m_app->readonly_directory_permissions), '');
        @chmod($this->flag, $m_app->readonly_file_permissions);
        return 0;
    }
}