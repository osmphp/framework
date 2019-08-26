<?php

namespace Osm\Framework\Testing\Browser;

use Osm\Core\Object_;

abstract class Elements extends Object_
{
    /**
     * @return string
     */
    abstract public function text();
}