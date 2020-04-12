<?php

namespace Osm\Ui\Forms\Views;

use Osm\Framework\Views\View;
use Osm\Ui\MenuBars\Views\MenuBar;

/**
 * @property string $title @required @part
 * @property View[] $views @required @part
 * @property View[] $views_ @required
 */
class Tab extends View
{
    public $template = 'Osm_Ui_Forms.tab';

    protected function default($property) {
        switch ($property) {
            case 'views': return [];
            case 'views_': return $this->sortViews($this->views);
        }
        return parent::default($property);
    }
}