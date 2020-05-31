<?php

namespace Osm\Data\Files\Commands;

use Osm\Core\App;
use Osm\Data\Files\File;
use Osm\Data\Files\Files;
use Osm\Framework\Console\Command;

/**
 * `clear:files` shell command class.
 *
 * @property Files $files @required
 */
class ClearFiles extends Command
{
    public function default($property) {
        global $osm_app; /* @var App $osm_app */

        switch ($property) {
            case 'files': return $osm_app[Files::class];
        }
        return parent::default($property);
    }

    public function run() {
        $this->files->clear([
            'dry_run' => $this->input->getOption('dry-run'),
            'full' => $this->input->getOption('full'),
            'callback' => function(File $file) {
                $this->output->writeln(osm_t(":file deleted", [
                    'file' => $file->filename_,
                ]));
            },
        ]);
    }
}