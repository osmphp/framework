<?php

namespace Osm\Framework\Areas;

use Osm\Core\App;
use Osm\Framework\Data\ObjectRegistry;

/**
 * @property string $area @required @part
 * @property Area $area_ @required
 * @property Areas|Area[] $areas
 */
class AreaObjectRegistry extends ObjectRegistry
{
    protected function default($property) {
        global $osm_app; /* @var App $osm_app */

        switch ($property) {
            case 'area_': return $this->areas[$this->area];
            case 'areas': return $osm_app->areas;
            case 'config_': return $this->createConfig($this->area);
        }
        return parent::default($property);
    }

    /**
     * @param string $area
     * @return array|mixed
     */
    protected function createConfig($area) {
        global $osm_app; /* @var App $osm_app */

        $result = $osm_app->config("{$area}/{$this->config}");

        if (!($parentArea = $this->areas[$area]->parent_area)) {
            return $result;
        }

        return osm_merge($this->createConfig($parentArea), $result);
    }

}