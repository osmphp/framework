<?php

namespace Osm\Framework\Config\Commands;

use Osm\Core\App;
use Osm\Core\Modules\BaseModule;
use Osm\Framework\Console\Command;

/**
 * @property BaseModule[] $modules @required
 */
class ShowConfig extends Command
{
    public function default($property) {
        global $osm_app; /* @var App $osm_app */

        switch ($property) {
            case 'modules': return $osm_app->modules;
        }

        return parent::default($property);
    }

    public function run() {
        if ($path = $this->input->getArgument('path')) {
            $this->showConfig($path);
        }
        else {
            $this->listConfigurationFiles();
        }
    }

    protected function listConfigurationFiles() {
        global $osm_app; /* @var App $osm_app */

        $filenames = [];
        foreach ($this->modules as $module) {
            if (!is_dir($osm_app->path("{$module->path}/config"))) {
                continue;
            }

            $this->collectConfigurationFiles($filenames, $module);
        }

        sort($filenames);

        foreach ($filenames as $filename) {
            $this->output->writeln($filename);
        }
    }

    protected function collectConfigurationFiles(&$filenames, BaseModule $module, $path = '') {
        global $osm_app; /* @var App $osm_app */

        foreach (new \DirectoryIterator($osm_app->path("{$module->path}/config" .
            ($path ? "/$path" : ''))) as $fileInfo)
        {
            if ($fileInfo->isDot()) {
                continue;
            }

            if ($fileInfo->isDir()) {
                $this->collectConfigurationFiles($filenames, $module,
                    ($path ? "{$path}/" : '') . $fileInfo->getFilename());
                continue;
            }

            if ($fileInfo->getExtension() != 'php') {
                continue;
            }

            $filename = ($path ? "{$path}/" : '') . pathinfo($fileInfo->getFilename(), PATHINFO_FILENAME);
            $filenames[$filename] = $filename;
        }
    }

    protected function showConfig($path) {
        global $osm_app; /* @var App $osm_app */

        $this->output->writeln($this->beautify(var_export($osm_app->config($path), true)));
    }

    protected function beautify($dump) {
        $dump = preg_replace('#(?:\A|\n)([ ]*)array \(#i', '[', $dump); // Starts
        $dump = preg_replace('#\n([ ]*)\),#', "\n$1],", $dump); // Ends
        $dump = preg_replace('#=> \[\n\s+\],\n#', "=> [],\n", $dump); // Empties
        $dump = preg_replace('#\)$#', "]", $dump);
        $dump = preg_replace('#\n\s*Osm\\\\Core\\\\Promise::__set_state\(array\(\n\s*\'object\' => \'localization\',\n\s*\'method\' => \'translate\',\n\s*\'args\' => \[\n\s*0 => \'([^\']*)\',\n\s*1 => \[\],\n\s*\],\n\s*\)\),#', "osm_t('$1'),", $dump);

        return $dump;
    }
}