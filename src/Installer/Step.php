<?php

namespace Osm\Framework\Installer;

use Illuminate\Console\OutputStyle;
use Osm\Core\Exceptions\NotImplemented;
use Osm\Core\Object_;

/**
 * @property OutputStyle $output @temp
 */
class Step extends Object_
{
    public function run() {
        throw new NotImplemented();
    }
}