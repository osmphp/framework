<?php

namespace Manadev\Framework\Installer;

use Illuminate\Console\OutputStyle;
use Manadev\Core\Exceptions\NotImplemented;
use Manadev\Core\Object_;

/**
 * @property OutputStyle $output @temp
 */
class Step extends Object_
{
    public function run() {
        throw new NotImplemented();
    }
}