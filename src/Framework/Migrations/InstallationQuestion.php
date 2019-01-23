<?php

namespace Manadev\Framework\Migrations;

use Manadev\Framework\Installer\Question;
use Manadev\Framework\Processes\Process;

class InstallationQuestion extends Question
{
    public function ask() {
        if (!$this->usesDb()) {
            return;
        }

        $value = $this->output->ask(m_("Enter database server name"), env('DB_HOST', 'localhost'),
            $this->required);
        Process::runInConsoleExpectingSuccess("php run env DB_HOST={$value}", true);
        putenv("DB_HOST={$value}");

        $value = $this->output->ask(m_("Enter database name"), env('DB_NAME'), $this->required);
        Process::runInConsoleExpectingSuccess("php run env DB_NAME={$value}", true);
        putenv("DB_NAME={$value}");

        $value = $this->output->ask(m_("Enter database user name"), env('DB_USER'), $this->required);
        Process::runInConsoleExpectingSuccess("php run env DB_USER={$value}", true);
        putenv("DB_USER={$value}");

        if (!env('DB_PASSWORD') || $this->yes != $this->output->choice(
            m_("Reuse database user password you entered last time?"),
            [$this->no, $this->yes], $this->yes))
        {
            $value = $this->output->askHidden(m_("Enter database user password"), $this->required);
            Process::runInConsoleExpectingSuccess("php run env -q DB_PASSWORD={$value}");
            putenv("DB_PASSWORD={$value}");
        }
    }

    protected function usesDb() {
        if (env('DB_NAME')) {
            return true;
        }

        return $this->yes == $this->output->choice(m_("Will your project use database?"),
            [$this->no, $this->yes], $this->yes);
    }
}