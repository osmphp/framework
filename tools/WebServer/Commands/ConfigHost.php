<?php

namespace Osm\Tools\WebServer\Commands;

use Osm\Framework\Console\Command;
use Osm\Tools\WebServer\Traits\DomainArgumentTrait;
use Osm\Framework\Console\Attributes\Option;

/**
 * @property ?string $ip #[Option]
 * @property string $actual_ip
 * @property string $filename
 * @property bool $remove #[Option('r')]
 *
 * @uses Option
 */
class ConfigHost extends Command
{
    use DomainArgumentTrait;

    public string $name = 'config:host';

    public function run(): void
    {
        $hosts = file_get_contents($this->filename);

        $regex = '/(?<sep>^|\n)(?<ip>\d+\.\d+\.\d+\.\d+)' .
            '\s+' . preg_quote($this->actual_domain, '/') . '(?<sep2>\n|$)/';

        if (preg_match($regex, $hosts, $match, PREG_OFFSET_CAPTURE)) {
            if ($this->remove) {
                $hosts = substr($hosts, 0, $match[0][1]) .
                    substr($hosts, $match[0][1] . strlen($match[0][0]));
                file_put_contents($this->filename, $hosts);
                $this->output->writeln(
                    "{$this->actual_domain} is removed from the hosts file.");
            }
            elseif($match['ip'][0] != $this->actual_ip) {
                $hosts = substr($hosts, 0, $match[0][1]) .
                    $match['sep'][0] .
                    "{$this->actual_ip} {$this->actual_domain}" .
                    $match['sep2'][0] .
                    substr($hosts, $match[0][1] . strlen($match[0][0]));
                file_put_contents($this->filename, $hosts);
                $this->output->writeln(
                    "{$this->actual_domain} is updated in the hosts file.");
            }
        }
        else {
            $hosts .= "\n{$this->actual_ip} {$this->actual_domain}";
            file_put_contents($this->filename, $hosts);
            $this->output->writeln(
                "{$this->actual_domain} is added to the hosts file.");
        }
    }

    protected function get_actual_ip(): string {
        return $this->ip ?? '127.0.0.1';
    }

    protected function get_filename(): string {
        return '/etc/hosts';
    }
}