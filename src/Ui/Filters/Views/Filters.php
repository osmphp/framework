<?php

namespace Osm\Ui\Filters\Views;

use Osm\Framework\Views\Views\Container;

/**
 * @property bool $empty_filters @required
 */
class Filters extends Container
{
    public $template = 'Osm_Ui_Filters.filters';

    protected function default($property) {
        switch ($property) {
            case 'empty_filters': return $this->areFiltersEmpty();
        }
        return parent::default($property);
    }

    protected function areFiltersEmpty() {
        foreach ($this->items as $filter) {
            if (!$filter->empty) {
                return false;
            }
        }

        return true;
    }
}