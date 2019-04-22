<?php

namespace Manadev\Data\OptionLists;

use Manadev\Core\App;
use Manadev\Data\OptionLists\Hints\OptionHint;
use Manadev\Framework\Areas\Area;
use Manadev\Framework\Areas\Areas as AreaRegistry;

/**
 * @property OptionHint[] $items @required @part Explicitly mark as cache-able
 * @property AreaRegistry|Area[] $areas @required
 */
class Areas extends OptionList
{
    protected function default($property) {
        global $m_app; /* @var App $m_app */

        switch ($property) {
            case 'areas': return $m_app->areas;
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