<?php

namespace Osm\Ui\MenuBars\Views;

use Osm\Core\App;
use Osm\Framework\Views\View;
use Osm\Framework\Views\Views\Page;

/**
 * @property Page $page @required
 * @property string $title @required @part
 * @property array $items @part
 */
class Heading extends View
{
    public $template = 'Osm_Ui_MenuBars.heading';

    protected function default($property) {
        global $osm_app; /* @var App $osm_app */

        switch ($property) {
            case 'page': return $osm_app->layout->select('#page');
            case 'title': return $this->page->title;
        }
        return parent::default($property);
    }
}