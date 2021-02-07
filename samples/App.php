<?php

declare(strict_types=1);

namespace Osm\Framework\Samples;

use Osm\Core\App as BaseApp;

class App extends BaseApp
{
    public static bool $load_dev_sections = true;
}