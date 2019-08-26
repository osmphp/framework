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
    protected function default($property) {
        global $m_app; /* @var App $m_app */

        switch ($property) {
            case 'page': return $m_app->layout->select('#page');
            case 'title': return $this->page->title;
        }
        return parent::default($property);
    }

    public $template = 'Osm_Ui_MenuBars.heading';
}