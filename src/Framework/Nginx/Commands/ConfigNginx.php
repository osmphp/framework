<?php

namespace Osm\Framework\Nginx\Commands;

use Osm\Core\App;
use Osm\Framework\Console\Command;
use Osm\Framework\Nginx\Module;
use Osm\Framework\Processes\Process;

/**
 * `config:nginx` shell command class.
 *
 * @property string $project @required
 * @property string $domain @required
 * @property string $fastcgi_pass @required
 * @property string $filename @required
 * @property string $contents @required
 * @property Module $module @required
 * @property string $template_filename @required
 * @property string $link @required
 */
class ConfigNginx extends Command
{
    public function default($property) {
        global $osm_app; /* @var App $osm_app */

        switch ($property) {
            case 'project': return basename($osm_app->path());
            case 'domain': return $this->input->getOption('domain')
                ?: $this->project;
            case 'fastcgi_pass': return $this->input->getOption('fastcgi_pass')
                ?: $this->getFastcgiPass();
            case 'filename': return "/etc/nginx/sites-available/{$this->project}";
            case 'link': return "/etc/nginx/sites-enabled/{$this->project}";
            case 'contents': return $this->getContents();
            case 'module': return $osm_app->modules['Osm_Framework_Nginx'];
            case 'template_filename': return $osm_app->path(
                "{$this->module->path}/project.nginx.config");
        }
        return parent::default($property);
    }

    protected function getContents() {
        return preg_replace_callback('/{(?<property>[a-z_]+)}/', function($match) {
            return $this->{$match['property']};
        }, file_get_contents($this->template_filename));
    }

    protected function getFastcgiPass() {
        $version = implode('.', array_slice(
            explode('.', phpversion()), 0, 2));

        $filename = is_file("/usr/sbin/php-fpm{$version}")
            ? "/var/run/php/php{$version}-fpm.sock"
            : "/var/run/php/php-fpm.sock";

        return "unix:{$filename}";
    }

    public function run() {
        file_put_contents($this->filename, $this->contents);
        chmod($this->filename, 0644);

        if (is_link($this->link)) {
            unlink($this->link);
        }

        symlink(realpath($this->filename), $this->link);

        Process::mustRun('service nginx restart');
    }

}