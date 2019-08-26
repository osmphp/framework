<?php

namespace Osm\Framework\Profiler;

use Osm\Core\Object_;

/**
 * @property string $name @required
 * @property float $total @required
 */
class Tag extends Object_
{
    /**
     * @var Timer[]
     */
    public $timers = [];
}