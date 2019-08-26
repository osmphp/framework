<?php

namespace Osm\Framework\Installer\Commands;

use Osm\Core\App;
use Osm\Framework\Console\Command;
use Osm\Framework\Installer\Module;
use Osm\Framework\Installer\Question;
use Osm\Framework\Installer\Questions;
use Osm\Framework\Installer\Requirement;
use Osm\Framework\Installer\Requirements;
use Osm\Framework\Installer\Step;
use Osm\Framework\Installer\Steps;
use Osm\Framework\Processes\Process;

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
        global $osm_app; /* @var App $osm_app */

        switch ($property) {
            case 'flag': return $osm_app->path("{$osm_app->temp_path}/installed.flag");
            case 'module': return $osm_app->modules['Osm_Framework_Installer'];
            case 'questions': return $this->module->questions;
            case 'steps': return $this->module->steps;
            case 'requirements': return $this->module->requirements;
            case 'yes': return (string)osm_t("Yes");
            case 'no': return (string)osm_t("No");
        }
        return parent::default($property);
    }

    public function run() {
        global $osm_app; /* @var App $osm_app */

        if (file_exists($this->flag)) {
            if (!$this->input->getOption('force')) {
                $this->output->writeln(osm_t("Project is already installed."));

                return 0;
            }
            unlink($this->flag);
        }

        if ($this->input->isInteractive()) {
            $this->output->writeln("");
            $this->output->writeln((string)osm_t("Welcome! This program will perform all necessary installation steps. But first, please answer some questions:"));
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

            if ($this->yes != $this->output->choice(osm_t("That's all the questions. Start installation?"),
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

        file_put_contents(osm_make_dir_for($this->flag, $osm_app->readonly_directory_permissions), '');
        @chmod($this->flag, $osm_app->readonly_file_permissions);
        return 0;
    }
}