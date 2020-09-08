<?php

namespace Osm\Framework\Sessions\Stores;

use Osm\Core\App;
use Osm\Core\Exceptions\NotSupported;
use Osm\Core\Object_;
use Osm\Framework\Settings\Settings;

/**
 * @property \SessionHandlerInterface $handler @required
 * @property string $name @required @part
 * @property bool $disabled @part
 *
 * @property string $session_class @part
 * @property int $time_to_live @required @part (in minutes)
 * @property string $cookie_name @required @part
 * @property string $cookie_path @required @part
 * @property string $cookie_domain @part
 * @property bool $cookie_secure @required @part
 * @property bool $cookie_http_only @required @part
 * @property string $cookie_same_site @part
 */
abstract class Store extends Object_
{
    public function offsetGet($offset) {
        return unserialize($this->handler->read($offset));
    }

    public function offsetSet($offset, $value) {
        $this->handler->write($offset, serialize($value));
    }

    public function offsetUnset($offset) {
        $this->handler->destroy($offset);
    }

    public function gc() {
        $this->handler->gc($this->time_to_live * 60);
    }
}