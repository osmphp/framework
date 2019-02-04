<?php

namespace Manadev\Framework\Composer;

use Manadev\Core\Exceptions\NotImplemented;
use Manadev\Core\Object_;
use Illuminate\Console\OutputStyle;

/**
 * @property string $name @required $part
 * @property string[] $events @required $part
 * @property OutputStyle $output @temp
 */
class Hook extends Object_
{
    public function run() {
        throw new NotImplemented();
    }
}