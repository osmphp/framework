<?php

namespace Osm\Framework\Emails;

use Osm\Core\Object_;
use Swift_Transport;

abstract class Transport extends Object_
{
    /**
     * @return Swift_Transport
     */
    abstract public function create();
}