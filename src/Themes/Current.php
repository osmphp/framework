<?php

namespace Osm\Framework\Themes;

use Osm\Core\App;
use Osm\Core\Object_;
use Osm\Framework\Settings\Settings;

/**
 * @property Settings $settings @required
 */
class Current extends Object_
{
    protected function default($property) {
        global $osm_app; /* @var App $osm_app */

        switch ($property) {
            case 'settings': return $osm_app->settings;
        }

        return parent::default($property);
    }

    public function get($area) {
        return $this->settings->{$area . '_theme'} ?: 'Osm_Blank';
    }
}