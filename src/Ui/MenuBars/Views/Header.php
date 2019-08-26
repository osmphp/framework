<?php

namespace Osm\Ui\MenuBars\Views;

use Osm\Framework\Views\View;

/**
 * @property View $menu @required @part
 */
class Header extends View
{
    public $template = 'Osm_Ui_MenuBars.header';

    public function __construct($data = []) {
        parent::__construct($data);

        $this->set([
            'menu' => MenuBar::new([
                'modifier' => $this->modifier,
                'items' => [],
            ]),
        ]);
    }
}