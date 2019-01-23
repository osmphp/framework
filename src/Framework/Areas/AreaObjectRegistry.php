<?php

namespace Manadev\Framework\Areas;

use Manadev\Core\App;
use Manadev\Framework\Data\ObjectRegistry;

/**
 * @property string $area @required @part
 * @property Area $area_ @required
 * @property Areas|Area[] $areas
 */
class AreaObjectRegistry extends ObjectRegistry
{
    protected function default($property) {
        global $m_app; /* @var App $m_app */

        switch ($property) {
            case 'area_': return $this->areas[$this->area];
            case 'areas': return $m_app->areas;
            case 'config_': return $this->createConfig($this->area);
        }
        return parent::default($property);
    }

    /**
     * @param string $area
     * @return array|mixed
     */
    protected function createConfig($area) {
        global $m_app; /* @var App $m_app */

        $result = $m_app->config("{$area}/{$this->config}");

        if (!($parentArea = $this->areas[$area]->parent_area)) {
            return $result;
        }

        return m_merge($this->createConfig($parentArea), $result);
    }

}