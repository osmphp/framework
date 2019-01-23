<?php

namespace Manadev\Framework\Profiler;

use Manadev\Core\Object_;

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