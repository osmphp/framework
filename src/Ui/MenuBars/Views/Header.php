<?php

namespace Manadev\Ui\MenuBars\Views;

use Manadev\Framework\Views\View;

/**
 * @property View $menu @required @part
 */
class Header extends View
{
    public $template = 'Manadev_Ui_MenuBars.header';

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