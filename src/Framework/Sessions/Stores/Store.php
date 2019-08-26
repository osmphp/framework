<?php

namespace Osm\Framework\Sessions\Stores;

use Osm\Core\App;
use Osm\Core\Exceptions\NotSupported;
use Osm\Core\Object_;
use Osm\Framework\Settings\Settings;

/**
 * @property \SessionHandlerInterface $handler @required
 * @property int $time_to_live @required (in minutes)
 * @property string $env @required
 * @property string $area @required
 * @property Settings $settings @required
 */
abstract class Store extends Object_
{
    protected function default($property) {
        global $osm_app; /* @var App $osm_app */

        switch ($property) {
            case 'env': return env('APP_ENV');
            case 'area': return $osm_app->area;
            case 'settings': return $osm_app->settings;
            case 'time_to_live': return $this->settings->{"{$this->area}_session_time_to_live"};
        }
        return parent::default($property);
    }

    public function offsetGet($offset) {
        return unserialize($this->handler->read($offset));
    }

    public function offsetSet($offset, $value) {
        $this->handler->write($offset, serialize($value));
    }

    public function offsetUnset($offset) {
        $this->handler->destroy($offset);
    }

    public function gc($timeToLiveInSeconds) {
        $this->handler->gc($timeToLiveInSeconds);
    }
}