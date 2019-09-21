<?php

namespace Osm\Framework\Env\Commands;

use Dotenv\Dotenv;
use Dotenv\Lines;
use Dotenv\Parser;
use Osm\Core\App;
use Osm\Framework\Console\Command;
use PhpOption\Option;

/**
 * @property string $path @required
 * @property Dotenv $dotenv @required
 * @property string $contents @required
 * @property string[] $entries @required
 */
class Env extends Command
{
    #region Properties
    public function default($property) {
        global $osm_app; /* @var App $osm_app */

        switch ($property) {
            case 'path': return $osm_app->path("{$osm_app->environment_path}/.env");
            case 'dotenv': return Dotenv::create($this->path);
            case 'contents': return @file_get_contents($this->path);
            case 'entries': return $this->getEntries();
        }
        return parent::default($property);
    }

    protected function getEntries() {
        $lines = Option::fromValue($this->contents, false);
        $contents = '';
        if ($lines->isDefined()) {
            $contents = $lines->get();
        }
        return Lines::process(preg_split("/(\r\n|\n|\r)/", $contents));
    }
    #endregion

    public function run() {
        $variables = $this->input->getArgument('variable');
        if (empty($variables)) {
            foreach ($this->all() as $name => $value) {
                $this->output->writeln("$name=$value");
            }
        }
        else {
            foreach ($variables as $name) {
                if (($pos = strpos($name, '=')) === false) {
                    $this->output->writeln("$name={$this->get($name)}");
                }
                else {
                    $this->output->writeln("$name");
                    $value = substr($name, $pos + 1);
                    $name = substr($name, 0, $pos);

                    $this->modify($name, $value);
                    putenv("$name=$value");
                }
            }
        }
    }

    protected function all() {
        $result = [];
        foreach ($this->entries as $entry) {
            list($key, $value) = Parser::parse($entry);
            $result[$key] = $value;
        }

        return $result;
    }

    protected function get($name) {
        foreach ($this->entries as $entry) {
            list($key, $value) = Parser::parse($entry);
            if ($key !== $name) {
                continue;
            }

            return $value;
        }

        return null;
    }

    protected function modify($name, $value) {
        foreach ($this->entries as $entry) {
            list($key) = Parser::parse($entry);
            if ($key !== $name) {
                continue;
            }

            $pos = mb_strpos($this->contents, $entry);
            $contents = mb_substr($this->contents, 0, $pos) .
                "{$name}={$value}" .
                mb_substr($this->contents, $pos + mb_strlen($entry));
            file_put_contents($this->path, $contents);
            return;
        }

        $contents = rtrim($this->contents);
        file_put_contents($this->path, "{$contents}\n{$name}={$value}\n");
    }
}