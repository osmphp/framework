<?php

namespace Manadev\Framework\Testing\Browser;

use Manadev\Core\Object_;

abstract class Document extends Object_
{
    /**
     * @param $selector
     * @return Elements
     */
    abstract public function find($selector);
}