<?php

namespace Osm\Framework\Testing\Browser;

use Osm\Core\Object_;

abstract class Document extends Object_
{
    /**
     * @param $selector
     * @return Elements
     */
    abstract public function find($selector);
}