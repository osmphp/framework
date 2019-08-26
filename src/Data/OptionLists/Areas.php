<?php

namespace Osm\Data\OptionLists;

use Osm\Core\App;
use Osm\Data\OptionLists\Hints\OptionHint;
use Osm\Framework\Areas\Area;
use Osm\Framework\Areas\Areas as AreaRegistry;

/**
 * @property OptionHint[] $items @required @part Explicitly mark as cache-able
 * @property AreaRegistry|Area[] $areas @required
 */
class Areas extends OptionList
{
    protected function default($property) {
        global $osm_app; /* @var App $osm_app */

        switch ($property) {
            case 'areas': return $osm_app->areas;
        }
        return parent::default($property);
    }

    protected function all() {
        $result = [];

        foreach ($this->areas as $area) {
            $result[$area->name] = (object)['title' => $area->title];
        }

        return collect($result);
    }

}