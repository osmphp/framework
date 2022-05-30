<?php

namespace Osm\Tools\WebServer\Commands;

use Osm\Framework\Console\Command;
use Osm\Tools\WebServer\Traits\DomainArgumentTrait;
use Osm\Framework\Console\Attributes\Option;

/**
 * @property string $available_filename
 * @property string $enabled_filename
 * @property string $filename
 * @property bool $remove #[Option('r')]
 *
 * @uses Option
 */
class InstallNginx extends Command
{
    use DomainArgumentTrait;

    public string $name = 'install:nginx';

    public function run(): void
    {
        if ($this->remove) {
            $this->output->writeln("Deleting '$this->enabled_filename' ...");
            if (is_link($this->enabled_filename)) {
                unlink($this->enabled_filename);
            }

            $this->output->writeln("Deleting '$this->available_filename' ...");
            if (is_file($this->available_filename)) {
                unlink($this->available_filename);
            }
        }
        else {
            $this->output->writeln("Creating '$this->available_filename' ...");
            copy('nginx_virtual_host.conf', $this->available_filename);

            $this->output->writeln("Creating '$this->enabled_filename' ...");
            if (is_link($this->enabled_filename)) {
                unlink($this->enabled_filename);
            }
            symlink($this->available_filename, $this->enabled_filename);

        }

        $this->output->writeln("Restarting Nginx ...");
        passthru('service nginx restart');

        $this->output->writeln("Done.");
    }

    protected function get_available_filename(): string {
        return "/etc/nginx/sites-available/{$this->filename}";
    }

    protected function get_enabled_filename(): string {
        return "/etc/nginx/sites-enabled/{$this->filename}";
    }

    protected function get_filename(): string {
        return $this->actual_domain;
    }
}