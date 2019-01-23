<?php

namespace Manadev\Framework\Data;

use Manadev\Core\Object_;

/**
 * @property string $name
 * @property int $sort_order
 */
class Advice extends Object_
{
    public function around(callable $next) {
        return $next();
    }
}