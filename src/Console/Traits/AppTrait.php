<?php

declare(strict_types=1);

namespace Osm\Framework\Console\Traits;

use Osm\Core\App;
use Osm\Framework\Console\Module;
use Symfony\Component\Console\Application as SymfonyConsole;

/**
 * @property SymfonyConsole $console
 */
trait AppTrait
{
    /** @noinspection PhpUnused */
    protected function get_console(): SymfonyConsole {
        /* @var App $this */

        /* @var Module $module */
        $module = $this->modules[Module::class];

        return $module->symfony;
    }
}