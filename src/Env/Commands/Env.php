<?php

namespace Osm\Framework\Env\Commands;

use Dotenv\Loader\Loader;
use Dotenv\Repository\Adapter\ArrayAdapter;
use Dotenv\Repository\Adapter\EnvConstAdapter;
use Dotenv\Repository\RepositoryBuilder;
use Dotenv\Repository\RepositoryInterface;
use Osm\Core\App;
use Osm\Framework\Console\Command;

/**
 * @property string $path @required
 * @property string $contents @required
 * @property string[] $lines @required
 * @property RepositoryInterface $repository @required
 * @property Loader $loader @required
 * @property string[] $values @required
 */
class Env extends Command
{
    #region Properties
    public function default($property) {
        global $osm_app; /* @var App $osm_app */

        switch ($property) {
            case 'path': return $osm_app->path("{$osm_app->environment_path}/.env");
            case 'contents': return file_get_contents($this->path);
            case 'lines': return file($this->path);
            case 'repository': return RepositoryBuilder::create()
                ->withReaders([new EnvConstAdapter()])
                ->withWriters([new ArrayAdapter()])
                ->immutable()
                ->make();
            case 'loader': return new Loader();
            case 'values': return $this->loader->load($this->repository, $this->contents);
        }
        return parent::default($property);
    }

    #endregion

    public function run() {
        $variables = $this->input->getArgument('variable');
        if (empty($variables)) {
            foreach ($this->values as $name => $value) {
                $this->output->writeln("$name=$value");
            }
        }
        else {
            foreach ($variables as $name) {
                if (($pos = strpos($name, '=')) === false) {
                    $value = $this->values[$name] ?? null;
                    $this->output->writeln("$name={$value}");
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

    protected function modify($name, $value) {
        if (preg_match('/\R?(?<line>' . preg_quote("{$name}=") . '.*)\R/u',
            $this->contents, $match, PREG_OFFSET_CAPTURE))
        {
            $this->contents =
                mb_substr($this->contents, 0, $match['line'][1]) .
                "{$name}={$value}" .
                mb_substr($this->contents,
                    $match['line'][1] + mb_strlen($match['line'][0]));
        }
        else {
            $this->contents .= "{$name}={$value}\n";
        }

        file_put_contents($this->path, $this->contents);
    }
}