<?php

namespace Manadev\Framework\Env\Commands;

use Manadev\Core\App;
use Manadev\Framework\Console\Command;
use Manadev\Framework\Env\File;

/**
 * @property File $file @required
 */
class Env extends Command
{
    public function default($property) {
        global $m_app; /* @var App $m_app */

        switch ($property) {
            case 'file': return $m_app->createRaw(File::class,
                $m_app->path("{$m_app->environment_path}/.env"));
        }
        return parent::default($property);
    }

    public function run() {
        $variables = $this->input->getArgument('variable');
        if (empty($variables)) {
            foreach ($this->file->all() as $name => $value) {
                $this->output->text("$name=$value");
            }
        }
        else {
            foreach ($variables as $name) {
                if (($pos = strpos($name, '=')) === false) {
                    $this->output->text("$name={$this->file->get($name)}");
                }
                else {
                    $this->output->text("$name");
                    $value = substr($name, $pos + 1);
                    $name = substr($name, 0, $pos);
                    $this->file->set($name, $value);
                }
            }
        }
    }
}