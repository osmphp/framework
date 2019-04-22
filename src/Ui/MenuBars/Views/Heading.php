<?php

namespace Manadev\Ui\MenuBars\Views;

use Manadev\Core\App;
use Manadev\Framework\Views\View;
use Manadev\Framework\Views\Views\Page;

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

    public $template = 'Manadev_Ui_MenuBars.heading';
}