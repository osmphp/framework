<?php

namespace Osm\Ui\Forms\Views;

use Osm\Framework\Views\Views\Container;
use Osm\Ui\MenuBars\Views\MenuBar;

/**
 * @property string $title @required @part
 * @property MenuBar $menu @part
 */
class Section extends Container
{
    public $template = 'Osm_Ui_Forms.section';
}