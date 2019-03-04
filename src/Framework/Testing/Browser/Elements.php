<?php

namespace Manadev\Framework\Testing\Browser;

use Manadev\Core\Object_;

abstract class Elements extends Object_
{
    /**
     * @return string
     */
    abstract public function text();
}