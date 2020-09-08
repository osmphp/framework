<?php

namespace Osm\Framework\Composer;

use Osm\Core\Exceptions\NotImplemented;
use Osm\Core\Object_;
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