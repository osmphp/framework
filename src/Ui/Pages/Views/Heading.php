<?php

namespace Osm\Ui\Pages\Views;

use Osm\Core\App;
use Osm\Framework\Views\View;
use Osm\Framework\Views\Views\Page;
use Osm\Ui\Menus\Views\MenuBar;

/**
 * @property Page $page @required
 * @property string $title @required @part
 * @property MenuBar $menu @required @part
 */
class Heading extends View
{
    public $template = 'Osm_Ui_Pages.heading';

    public function __construct($data = []) {
        parent::__construct($data);

        $this->menu = $this->layout->view($this, MenuBar::new([
            'horizontal_align' => 'right',
        ]), 'menu');
    }

    protected function default($property) {
        global $osm_app;
        /* @var App $osm_app */

        switch ($property) {
            case 'page': return $osm_app->layout->select('#page');
            case 'title': return $this->page->title;
        }

        return parent::default($property);
    }
}