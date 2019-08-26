<?php

namespace Osm\Framework\Env\Commands;

use Osm\Core\App;
use Osm\Framework\Console\Command;
use Osm\Framework\Env\File;

/**
 * @property File $file @required
 */
class Env extends Command
{
    public function default($property) {
        global $osm_app; /* @var App $osm_app */

        switch ($property) {
            case 'file': return $osm_app->createRaw(File::class,
                $osm_app->path("{$osm_app->environment_path}/.env"));
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