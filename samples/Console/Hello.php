<?php

/** @noinspection PhpUnused */
/** @noinspection PhpUnusedAliasInspection */
declare(strict_types=1);

namespace Osm\Framework\Samples\Console;

use Osm\Framework\Console\Command;
use Osm\Framework\Console\Attributes\Option;
use Osm\Framework\Console\Attributes\Argument;

/**
 * @property bool $caps #[Option] If specified, the person name is upper-cased
 * @property string $person_name #[Argument] The person to greet
 */
class Hello extends Command
{
    public string $name = 'hello';
    public string $description = 'A sample command';

    public function run(): void {
        $name = $this->caps ? strtoupper($this->person_name) : $this->person_name;
        $this->output->writeln("Hello, {$name}");
    }
}