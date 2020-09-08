<?php

namespace Osm\Framework\Gulp\Commands;

use Osm\Core\App;
use Osm\Framework\Console\Command;
use Osm\Framework\Gulp\FileWatcher;
use Osm\Framework\Gulp\FileWatchers;
use Osm\Framework\Gulp\Module;

/**
 * `notify:data-changed` shell command class.
 *
 * @property string[] $paths @required
 * @property Module $module @required
 * @property FileWatchers|FileWatcher[] $file_watchers @required
 */
class NotifyDataChanged extends Command
{
    public function default($property) {
        global $osm_app; /* @var App $osm_app */

        switch ($property) {
            case 'paths': return $this->getPaths();
            case 'module': return $osm_app->modules['Osm_Framework_Gulp'];
            case 'file_watchers': return $this->module->file_watchers;
        }
        return parent::default($property);
    }

    protected function getPaths() {
        $result = $this->input->getArgument('path');

        if ($files = $this->input->getOption('filelist')) {
            $result = array_merge($result, file($files));
        }

        return $result;
    }

    public function run() {
        foreach ($this->file_watchers as $fileWatcher) {
            $fileWatcher->handle($this->paths);
        }
    }
}